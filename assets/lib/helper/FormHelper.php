<?php

namespace app\common\helper;

use think\Validate;
use think\Model;

/**
 * Class Request 跟表单提交数据相关的工具类
 * @package app\common\helper
 */
final class FormHelper
{
    const DATA_UNCHANGED = 10000;
    const DATA_SAVED_SUCCESS = 10001;
    const DATA_SAVED_FALSE = 10002;
    const DATA_VALID_FAILED = 10003;
    const DATA_GET_NULL = 10004;

    /**
     * 从给定的字段名列表自动获取组成数据集合，$data中的字段必须为数据库中真实的字段
     * @param array $names
     * @return array
     */
    public static function getInputDataByNames($names = [])
    {
        $data = [];
        foreach ($names as $name) {
            //当字段类型为 checkbox 的时候，提交的值可能是 null ，所以设置为 0
            if ($name == 'status' || preg_match('/^is_/', $name)) {
                $data[$name] = (input($name) != null) ? input($name) : '0';
            } else {
                $data[$name] = (input($name) != null) ? input($name) : '';
            }
        }
        return $data;
    }

    /**
     * 根据给出的提交的字段名获取 Request 中输入的数据，进行验证并保存，返回保存的结果或验证失败信息
     * @param Validate $validator 验证器
     * @param array $fields 需要验证的字段名称，采用 '[!]name[:alias_name]' 的格式，!表示不保存的字段，:alias_name表示用后面的值来保存
     * @param Model $model 引用的数据模型
     * @param array $preset_data 单独设置的数据值
     * @return array 错误提示数组，['code' => '', 'msg' =>'' ]
     */
    public static function validateAndSaveData(Validate $validator, array $fields, Model &$model, array $preset_data = [])
    {
        //设置用于校验与保存的字段名，如果字段名字前面有 ! 号则说明此字段不需要保存
        $validate_fields = [];
        $save_fields = [];
        $alias_fields = [];
        $mdata = $model->getData();

        foreach ($fields as &$field) {
            //判断是否存在值的别名保存
            if (strpos($field, ':')) {
                $tmp = explode(':', $field);
                $field = $tmp[0];
                array_push($alias_fields, $tmp);
            }
            if (strpos($field, '!') === 0) {
                array_push($validate_fields, substr($field, 1));
            } else {
                //判断未曾修改提交的值，如果值未曾修改，则不需要校验
                if (is_array($mdata)) {
                    if (array_key_exists($field, $mdata)) {
                        if (strval($mdata[$field]) == input($field)) {
                            unset($field);
                            continue;
                        }
                    }
                }
                array_push($validate_fields, $field);
                array_push($save_fields, $field);
            }
        }
        //如果存在预设数据，则确认预设数据值保存与验证的范围内
        $validate_fields = array_merge($validate_fields, array_keys($preset_data));
        $save_fields = array_merge($save_fields, array_keys($preset_data));

        //获取校验数据
        $data = FormHelper::getInputDataByNames($validate_fields);
        $data = array_merge($data, $preset_data);

        //动态设置场景进行验证
        $validator->scene('scene', $validate_fields);
        if (!$validator->scene('scene')->check($data)) {
            return [
                'code' => self::DATA_VALID_FAILED,
                'msg' => $validator->getError()
            ];
        } else {
            unset($validator);
            //设置 alias 属性的值
            foreach ($alias_fields as $alias_field) {
                $data[$alias_field[0]] = $data[$alias_field[1]];
            }
            //TODO 由于 tp5 框架变更了对 allowField 的处理方式，所以暂时去除 $save_fields 的应用
            //设置并解决 update_time/create_time 插入问题
            $class = new \ReflectionClass($model);
            $properties = $class->getDefaultProperties();
            if ($class->hasProperty('createTime')) {
                $value = $properties['createTime'];
                if (false !== $value) array_push($save_fields, 'create_time');
            }

            if ($class->hasProperty('updateTime')) {
                $value = $properties['updateTime'];
                if (false !== $value) array_push($save_fields, 'update_time');
            }
            //保存数据
            FormHelper::filtrateSaveData($save_fields, $data);
            FormHelper::alterSaveDataValue($data);
            $result = $model->save($data);

            //根据保存返回值来返回操作结果
            if ($result === null) return [
                'code' => self::DATA_GET_NULL,
                'msg' => '框架返回空值'
            ];

            if ($result === '0' || $result === 0) return [
                'code' => self::DATA_UNCHANGED,
                'msg' => '提交的数据与现有数据一致，未修改'
            ];

            if ($result === false) return [
                'code' => self::DATA_SAVED_FALSE,
                'msg' => '数据保存失败'
            ];

            if ((is_numeric($result) && (int)$result > 0) || $result === true) return [
                'code' => self::DATA_SAVED_SUCCESS,
                'msg' => '数据保存成功！'
            ];

            return [
                'code' => self::DATA_SAVED_FALSE,
                'msg' => '数据保存失败'
            ];
        }
    }

    /**
     * 直接校验数据并返回结果
     * @param Validate $validator 验证器
     * @param array $fields 需要验证的字段名称
     * @return array|bool
     */
    public static function validate(Validate $validator, $fields)
    {
        $data = FormHelper::getInputDataByNames($fields);
        $validator->scene('scene', $fields);

        if (!$validator->scene('scene')->check($data)) {
            return $validator->getError();
        } else {
            return true;
        }
    }

    /**
     * 在数据保存之前，过滤掉多余的数据项
     * @param array $save_fields
     * @param array $data
     */
    protected static function filtrateSaveData($save_fields = [], &$data = [])
    {
        foreach ($data as $key => &$item) {
            //TODO 现在不过滤id，以后修改为不过滤所有 $pk
            if (!in_array($key, $save_fields) && $key != 'id') unset($data[$key]);
        }
    }

    /**
     * 过滤所有数据的值，如果是 check 类型，且为 on 的，则自动转换为 1
     * @param array $data
     */
    protected static function alterSaveDataValue(&$data = [])
    {
        foreach ($data as &$item) {
            if ($item === 'on' || $item === 'yes') $item = '1';
        }
    }
}
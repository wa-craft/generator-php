<?php

namespace app\common\model;

use think\Model;

/**
 * Class DefaultModel
 * @package app\common\model
 */
abstract class DefaultModel extends Model
{
    const DATA_UNCHANGED = 10000;
    const DATA_SAVED_SUCCESS = 10001;
    const DATA_SAVED_FALSE = 10002;
    const DATA_VALID_FAILED = 10003;
    const DATA_GET_NULL = 10004;

    /**
     * 处理保存的数据，并根据框架的返回值来组合应用的返回值
     * @param $result
     * @return array
     */
    protected function makeSavedResult($result)
    {
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
            'msg' => '数据保存失败：' . $this->getError()
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

    /**
     * 获取模型名称
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 过滤请求中提交的字段，判断是否是新增，如果是新增，按照字段过滤，返回数组;如果不是新增，返回更新后的数据
     * @return array
     */
    public function filterRequestData($preset_data)
    {
        $this_data = $this->getData();
        $request_data = array_merge(request()->param(), $preset_data);

        if (empty($this_data)) {
            //新增
            $data = [];
            $fields = self::getTableFields();
            foreach ($request_data as $key => $value) {
                if (in_array($key, $fields)) {
                    if ($key == 'status' || preg_match('/^is_/', $key)) {
                        $data[$key] = (input($key) != null) ? input($key) : '0';
                    } else {
                        $data[$key] = input($key) ?? $preset_data[$key] ?? '';
                    }
                }
            }
            return $data;
        } else {
            //更新
            $diff_data = array_diff_assoc($request_data, $this_data);
            $changed_data = [];
            $available_keys = array_keys($this_data);

            foreach ($available_keys as $field) {
                if (array_key_exists($field, $diff_data)) {
                    $changed_data[$field] = $diff_data[$field];
                }
            }
            return array_merge($this_data, $changed_data);
        }
    }

    /**
     * 保存当前数据对象
     * @access public
     * @param array $preset_data 预置数据
     * @param array $where 更新条件
     * @param string $sequence 自增序列名
     * @return array
     */
    public function saveRequestData($preset_data = [], $where = [], $sequence = null)
    {
        $update_data = $this->filterRequestData($preset_data);
        if (empty($update_data)) {
            return [
                'code' => self::DATA_UNCHANGED,
                'msg' => '数据未修改'
            ];
        } else {
            //处理预置数据
            self::alterSaveDataValue($update_data);
            $result = $this->validate(true)->save($update_data);
            return $this->makeSavedResult($result);
        }
    }
}
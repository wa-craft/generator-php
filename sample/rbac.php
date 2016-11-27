<?php
/**
 * RBAC样例
 */
return [
    'name' => 'rbac',
    'domain' => 'rbac.vm',
    'applications' => [
        [
            'name' => 'application',
            'namespace' => 'app',
            'portal' => 'index',
            'comment' => '',
            'modules' => [
                'rbac'
            ]
        ]
    ]
];

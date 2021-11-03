<?php

namespace app\controller;

use app\BaseController;
use Elasticsearch\ClientBuilder;
use think\App;
use think\facade\Db;

class Elastic extends BaseController
{
    private $client;
    private $index_name;
    private $type;

    /**
     * 构造函数
     * Elastic constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $params = array(
            '127.0.0.1:9200'
        );
        $this->client = ClientBuilder::create()->setHosts($params)->build();
    }


    /**
     * 设置索引跟类型
     * @param string $index_name
     * @param string $type
     */
    public function set($index_name = 'index', $type = 'type')
    {
        $this->index_name = $index_name;
        $this->type = $type;
    }


    /**
     * mysql涉及到搜索的数据插入文档
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sync()
    {
        $list = Db::name("test_es")->select();
        foreach ($list as $k => $v) {
            $r = $this->add_doc($v['id'], $v);
        }
    }


    /**
     * 搜索
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        //设置索引跟类型
        $this->set('test_es', 'test_es');
        //创建索引
        //$this->create_index();
        //创建文档结构
        //$r = $this->create_mappings();
        //获取文档结构，查看映射
        //$r = $this->get_mapping();
        //$this->sync();
        //return show('1',200);
        //删除索引
        //$this->delete_index();
        /*$id = 1;
        //文档是否存在
        $r = $this->exists_doc($id);
        if ($r === true) {
            //获取文档信息
            $r = $this->get_doc($id);
            //修改文档
            //$r = $this->update_doc($id, 'name', '小明99');
            //删除文档
            //   $this->delete_doc($id);
        }*/
        //$r = $this->search_doc("你想怎样啊");
        $r = $this->search_doc("小明");

        dump($r);
    }


    /**
     * 删除索引
     * @return array
     */
    public function delete_index()
    {
        $params = ['index' => $this->index_name];

        return $this->client->indices()->delete($params);
    }

    /**
     * 创建索引
     * @return array|callable
     */
    public function create_index()
    {
        // 只能创建一次
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'body' => []
        ];

        return $this->client->index($params);
    }


    /**
     * 获取文档
     * @param $id
     * @return array|callable
     */
    public function get_doc($id)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'id' => $id
        ];

        return $this->client->get($params);
    }


    /**
     * 创建文档模板
     * @return array
     */
    public function create_mappings()
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'include_type_name' => true,//7.0以上版本必须有
            'body' => [
                'properties' => [
                    'id' => [
                        'type' => 'long', // 整型
                    ],
                    'name' => [
                        //5.x以上已经没有string类型。如果需要分词的话使用text，不需要分词使用keyword。
                        'type' => 'text', // 字符串型
                    ],
                    'profile' => [
                        'type' => 'text',
                    ],
                    'age' => [
                        'type' => 'long',
                    ],
                    'job' => [
                        'type' => 'text',
                    ],
                ]
            ]
        ];

        return $this->client->indices()->putMapping($params);
    }


    /**
     * 查看映射
     * @return array
     */
    public function get_mapping()
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'include_type_name' => true,//7.0以上版本必须有
        ];

        return $this->client->indices()->getMapping($params);
    }


    /**
     * 添加文档
     * @param string $id
     * @param array $doc 跟创建文档结构时properties的字段一致
     * @return array|callable
     */
    public function add_doc(string $id, array $doc)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'id' => $id,
            'body' => $doc
        ];

        return $this->client->index($params);
    }


    /**
     * 判断文档存在
     * @param int $id
     * @return bool
     */
    public function exists_doc($id = 1)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'id' => $id
        ];

        return $this->client->exists($params);
    }


    /**
     * 更新文档
     * @param $id
     * @param $key
     * @param $value
     * @return array|callable
     */
    public function update_doc($id, $key, $value)
    {
        // 可以灵活添加新字段,最好不要乱添加
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'id' => $id,
            'body' => [
                'doc' => [
                    $key => $value
                ]
            ]
        ];

        return $this->client->update($params);
    }


    /**
     * 删除文档
     * @param int $id
     * @return array|callable
     */
    public function delete_doc($id = 1)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'id' => $id
        ];

        return $this->client->delete($params);
    }


    /**
     * 查询表达式搜索
     * @param $keywords
     * @param $from
     * @param $size
     * @return array
     */
    public function search_doc1($keywords, $from, $size)
    {
        return [
            'query' => [
                "match" => [
                    //"name"=> $keywords, 或者
                    "name" => [
                        'query' => $keywords,
                        'boost' => 3, // 权重
                    ],
                ],
            ],
            'from' => $from,
            'size' => $size
        ];
    }


    /**
     * 短语搜索
     * @param $keywords
     * @param $from
     * @param $size
     * @return array
     */
    public function search_doc3($keywords, $from, $size)
    {
        return [
            'query' => [
                "match_phrase" => [
                    //"name"=> $keywords, 或者
                    "name" => [
                        'query' => $keywords,
                        'boost' => 3, // 权重
                    ],
                ],
            ],
            'from' => $from,
            'size' => $size
        ];
    }


    /**
     * 高亮搜索
     * @param string $keywords
     * @param $from
     * @param $size
     * @return array
     */
    public function search_doc4(string $keywords, $from, $size): array
    {
        return [
            'query' => [
                "match_phrase" => [
                    //"name"=>$keywords, 或者
                    "name" => [
                        'query' => $keywords,
                        'boost' => 3, // 权重
                    ],
                ],
            ],
            'highlight' => [
                "fields" => [
                    //必须加object，要不然转json时，这里依然是数组，而不是对象
                    "name" => (object)[]
                ]
            ],
            'from' => $from,
            'size' => $size
        ];
    }


    /**
     * 搜索结果增加分析
     * @param string $keywords
     * @param int $from
     * @param int $size
     * @return array
     */
    public function search_doc5(string $keywords, int $from, int $size)
    {
        return [
            'query' => [
                'bool' => [
                    //必须匹配
                    "must" => [
                        "match" => [
                            "profile" => $keywords,
                        ]
                    ],
                    //应该匹配
                    'should' => [
                        ['match' => [
                            'profile' => [
                                'query' => $keywords,
                                'boost' => 3, // 权重
                            ]]],
                        ['match' => ['name' => [
                            'query' => $keywords,
                            'boost' => 2,
                        ]]],
                    ],
                    //复杂的搜索 限制年龄大于25岁
                    'filter' => [
                        "range" => [
                            "age" => ["gt" => 25]
                        ]
                    ]
                ],
            ],
            'highlight' => [
                "fields" => [
                    //必须加object，要不然转json时，这里依然是数组，而不是对象
                    "name" => (object)[]
                ]
            ],
            'aggs' => [
                "result" => [
                    //terms 桶 统计文档数量
                    "terms" => [
                        "field" => "age"
                    ]
                ],
                "avg" => [
                    //avg 平均值
                    "avg" => [
                        "field" => "age"
                    ]
                ],
                "max" => [
                    //max 最大值
                    "max" => [
                        "field" => "age"
                    ]
                ],
                "min" => [
                    //avg 最小值
                    "min" => [
                        "field" => "age"
                    ]
                ],
            ],
            'from' => $from,
            'size' => $size,
        ];
    }


    /**
     * 使用过滤器 filter
     * @param $keywords
     * @param $from
     * @param $size
     * @return array
     */
    public function search_doc2($keywords, $from, $size)
    {
        return ['query' => [
            'bool' => [
                //必须匹配
                "must" => [
                    "match" => [
                        "name" => $keywords,
                    ]
                ],
                //应该匹配
                'should' => [
                    ['match' => [
                        'profile' => [
                            'query' => $keywords,
                            'boost' => 3, // 权重
                        ]]],
                    ['match' => ['name' => [
                        'query' => $keywords,
                        'boost' => 2,
                    ]]],
                ],
                //复杂的搜索 限制年龄大于25岁
                'filter' => [
                    "range" => [
                        "age" => ["gt" => 25]
                    ]
                ]
            ],

        ],
            //  'sort' => ['age'=>['order'=>'desc']],
            'from' => $from,
            'size' => $size
        ];
    }


    /**
     * 查询文档 (分页，排序，权重，过滤)
     * @param $keywords
     * @param int $from
     * @param int $size
     * @return array|callable
     */
    public function search_doc($keywords, $from = 0, $size = 12)
    {
        //$query = $this->search_doc5($keywords, $from, $size);
        $query = $this->search_doc1($keywords, $from, $size);
        $params = [
            'index' => $this->index_name,
            'type' => $this->type,
            'body' => $query
        ];

        return $this->client->search($params);
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title', 'user_id','desc'
    ];

    // 创建问题API
    public function add(){

        /*检查用户是否登录*/
        if(!user_ins()->is_logged_in())
            return ['status'=>0,'msg'=>'请登录!'];

        /*检查是否存在标题*/
        if(!rq('title'))
            return ['status'=>0,'msg'=>'标题不能为空!'];

        $this->title = rq('title');
        $this->user_id = session('user_id');

        /*如果存在描述就添加描述*/
        if (rq('desc'))
            $this->desc = rq('desc');

        /*保存*/
        return $this->save() ? ['status'=>1,'id'=>$this->id] : ['status'=>0,'msg'=>'保存失败！'];
    }

    // 更新问题API
    public function change(){
        /*检查用户是否登录*/
        if (!user_ins()->is_logged_in())
            return ['status'=>0,'msg'=>'请登录!'];

        /*检查传参中是否有ID*/
        if (!rq('id'))
            return ['status'=>0,'msg'=>'ID不可为空!'];

        /*获取指定ID的model*/
        $question = $this->find(rq('id'));

        /*判断问题是否存在*/
        if ($question)
            return ['status'=>0,'msg'=>'用户问题不存在!'];

        if ($question->user_id != session('user_id'))
            return ['status'=>0,'msg'=>'无权限操作'];

        if (rq('title'))
            $question->title = rq('title');

        if (rq('desc'))
            $question->desc = rq('desc');

        /*保存数据*/
        return $question->save() ? ['status'=>1,'msg'=>'更新成功!'] : ['status'=>0,'msg'=>'更新失败！'];
    }

    // 查看问题API
    public function read(){

        /*请求参数中是否有ID，如果有ID直接返回ID所在的行*/
        if (rq('id'))
            return ['status'=>1,'data'=>$this->find(rq('id'))];

        /*limit 条件*/
        $limit = rq('limit') ? : 15;

        /*skip 条件 用于分页*/
        $skip = (rq('page') ? rq('page') -1 : 0) * $limit;

        /*构建query并返回collection对象数据*/
        $r = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id','title','desc','user_id','created_at','updated_at'])
            ->keyBy('id');

        return ['status'=>1,'data'=>$r];
    }

}

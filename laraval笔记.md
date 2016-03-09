#laravel

##安装

###composer

	composer是一种PHP类库依赖关系管理器
	自动安装php开源插件
	安装时出现问题一般是网络原因(国外网络,建议用vpn代理)

###使用composer安装larvel
* composer create-project laravel/laravel --prefer-dist d:/myObj

	使用composer创建一个项目 laravel目录下的larvel 期望下载压缩文件 下载安装到 d:/myObj文件夹
	
	注意:linux下web启动laravel后可能出现白屏，此时应当修改storage和vendor文件夹的拥有者(apache或ngix)
	
	storage存放日志，缓存，session文件
	
	vendor存放依赖文件，插件，库

###路由

* 文件/app/Http/Requests/routes.php

	Route::get('/','WelcomeController@index');

	当以get请求访问网站根目录时，就调用Welcome控制器的index方法

	第二个参数可以是匿名函数

	Route::post('test',function(){
		return 'post请求';
	});

	Route::any('test',function(){
		return '任何请求';
	});

###控制器


* 文件夹/app/Http/Controllers
#####控制器创建方法
1. 像Tp一样手动在/app/Http/Controllers目录下创建
	
	eg:
	创建Test控制器nihao方法并访问

	/app/Http/Controllers/TestController.php     //创建test控制器

	`class TestController extends BaseController  //nihao方法
	{
	    function nihao(){
	        return 'nihao';
	    }
	}`

	在/app/Http/Requests/routes.php 里访问

	Route('/','TestController@nihao');

2. 命令行用artisan插件创建
	
	进入laraval根目录

	php artisan make:controller Mycontroller //命令行执行 创建my控制器

###查看所有路由

	php artisan route:list

###创建一个控制器里可以访问任何默认(show,create,edit....)方法的路由（不建议用资源路由）

	Route::resource('user','UserController');  //可以get访问user控制器的的所有默认方法

##视图

* 目录
	
	/resources/views/      welcome.blabe.php
	 //用的是blade模板引擎
* 调用

	在控制器里 return view('模板名'); 

* 传参

	1. return view('tplName')->whith('变量名',$val);

		$name='小花';
        $time=date('Y-m-d H:i:s');
        return view('tplName')->with('name',$name)->with('time',$time);

	2.  $name='小花';
        $time=date('Y-m-d H:i:s');
        $data=[
            'name'=>$name,
            'time'=>$time,
        ];
        return view('tplName',$data);

	3.  $name='小花';
        return view('tplName',compact('name'));
	
> view()=view::make('tplName');

###blade模板引擎

1. 变量 {{$val}}  （可以写有输出的函数）
	
	为空处理 {{$val or '为空'}}
	
	不解析直接输出 @{{$val}}
	
	解析js脚本{!! "<script>alert('ok')</script>" !!}
	
	
	不解析html直接输出 {{{"html"}}}

2. 分支
	
	@if($name)	
		<div>hello{{$name}}</div>
	@else
		<div>no name</div>
	@endif

3. 遍历(循环)
	@foreach($list as $val)
		<div>{{$val}}</div>
	@endforeach

	@for($i=0;$i<10;$i++)
		<li>{{$i}}</li>
	@endfor

	@while($i<10)
		<li>{{$i}}</li>
		<?php $i++;?>
	@endwhile
		

4.子模板（引入公共文件）
	
	@include('dir.tplName')
	

###laraval框架部署

1. 全局配置
	
	在laraval根目录下找到.env或者.env.example

	配置项目运行环境和数据库配置
	
2. 项目配置 /config/
	
	app.php
	
	database.php

	session.php

3. 服务器维护或者数据出问题时(禁止访问)503服务器错误
	
	php artisan down //禁止访问
	
	php artisan up //可以访问  

	错误页面 /resources/views/errors/503.blade.php

###模型(eloquent)

1. 创建模型
	
	php artisan make:modle User	

	protected $table="表名";

	protected $fillable=[]; //可操作的字段

	protected $guarded=[];  //不可操作的字段 主键

	protected $hidden=[]; //不能取出的字段 密码

	protected $primaryKey='id'; //指定主键

	public $timestamps=true;  //默认检查每张表有没有create_at update_at字段

2. 数据的查询

	$this->all();  //所有数据 或者 get()
	$this->find(1); //第一条数据

	$this->where('field','val')->get();  //条件查询
	
	$this->where('field','>','val')->get(); //判断查询

3. 数据的增加
	
	$this->fieldName=val;  //AR模型

	$this->save();			//增加


	$this->fill($_POST);  //增加所有
	$this->save();

4. 数据的更新

	$row=$this->find(8); //找到id为8的记录
	$row->fieldName=newVal; //AR映射
	$this->save();

	$list=$this->where('fieldName','>','val'); //多条数据的更新
	$list->update(['fieldName'=>'newVal']); //AR映射并保存

5. 数据的删除
	
	$row=$this->find(8); //找到id为8的记录
	$row->delete();		//删除

> dd($var) 函数=var_dump($var);die;
> collect($var) 函数 数组转集合
> $val->all()  集合转原型

### 集合
	
	$collect->contains('val'); //判断集合中有没有val值
	$collect->has('key');  //判断集合中有没有key键
	$collect->take(2); //取出前两个值
	$collect->take(-2)' //取出后两个值

###get和post

	Input::get('key','默认值'); //获取get数据 Requset::query();
	Request::all(); //所有类型提交数据
	Requset::has('key'); //判断值是否存在
	Request::exists('key'); //判断键是否存在
	Request::only(key1,key2...); //只要字符串列表的数据
	Request::except(key1,key2...); //除了字符串列表的数据
	Request::url();  //请求的URL
	Request::fullUrl(); //完整的url包括参数
	表单提交数据
	加上<input type="hidden" name="_token" value="{{csrf_token()}}">

###输入历史

	Request::flash(); //把请求数据存到session
	Requset::old();  //取出session中的请求数据

	Request::flashOnly(key1,key2...); //只存
	Request::flashExcept(key1,key2...); //不存

###上传文件
	
	Request::file(); //上传的所有文件
	Request::hasFile(key); //是否存在key文件

	$file=Request::file(key);
	//返回文件上传类
	$file->getSize(); //文件大小
	$file->getClientOriginalName(); //文件上传之前的名字
	$file->getClientOriginalExtension(); //文件上传之前的后缀名

###会话

	Session::all() // _previous[url] //历史url  所有session

	Session::put('abc','123') //设置abc

	Session::get('abc')  //获取session

	Session::has('abc') //判断session是否存在

	Session::forget('abc') //删除session

	Session::pull('abc') //弹出session 拿出来在删除掉(一次)

	session(array(key=>val)) //函数存储

###session入库

	.env 文件里的 SESSION_DRIVER=file 改成database

	php artisan session:table  //创建session存储表

	composer dump-autoload  //原因不明

	php artisan migrate //原因不明

	完成以上步骤在数据库查看sessions

###数据验证

	$data=Request::all();       //要验证的数据
	
	$res=validator::make($data,[
		'username'=>'required',  //必须
		'phone'=>'numeric',		//数字
		'id'=>'numeric|required',  //数字，必填
		'description'=>'min:12|max:120', // 长度在12-120  (或者 between:12,120)
		'email'=>'unique:user',   //在user表中唯一
	]);

	if($res->fails()){          //判断结果
		return $res->errors();
	}else{
		return 'ok';
	}


###哈希

	Hash::make($password);  //加密
	Hash::check($password,$rePassword);  //检查    ??查阅hash是怎样加密的

###实用函数

	head($arr);  //数组中的第一个元素值

	array_only($arr,['name','age']);  //返回数组中键名为name和age的元素

	array_first($arr,function($key,$val){
		return $val>30;
	});  //返回大于30的元素

	array_add($arr,$key,$val);  //相当于 $arr[$key]=$val;

	array_except($arr,'name'); //返回除了name的元素

	array_flatten($arr);  //返回所有值，不管多少维
	
	array_where($arr,function($key,$val){
		return is_string($val);                 //返回所有字符串
	});
	
	last($arr);   //数组中的最后一个元素值


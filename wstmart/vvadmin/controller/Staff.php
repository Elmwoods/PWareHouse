<?php
namespace  wstmart\vvadmin\Controller;
use wstmart\vvadmin\model\VvoffStaff as M;
use wstmart\vvadmin\model\VvoffEnstaff as MM;
use  think\Validate;
use  think\Request;
use  think\Controller;
class  Staff  extends Controller
 {
 	//中文版
	public function dolist()
	{
		$m=new M;
		$data=$m->select();
		$this->assign("data",$data);
		return  $this->fetch('dolist');
	}
	public function edit()
	{
		$m=new M;
		$ii=input('param.');
		$data=$m->where('id',$ii['id'])->find();
		$this->assign("data",$data);
		return $this->fetch('edit');
	}
	
	public function delete()
	{
		$m= new M;
		$ii=input('param.');
		$data=$m->where('id',$ii['id'])->delete();
		if ($data) {
			$this->success('删除成功,不要忘记删除英文版哦0.0','dolist', '', 1);
		}
		
	}
	public function add()
	{
		return $this->fetch('add');
	}
	public function doedit()
	{
		$m= new M;
		$ii=input('post.');
		$dd=$m->where('id',$ii['id'])->value('headimg');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'name'=>$ii['name'],
			'job'=>$ii['job'],
			'motto'=>$ii['motto'],
			'introduction'=>$ii['introduction'],
			'is_see'=>$ii['is_see']
			];

		$validate = new Validate([
			['name'  ,'require','请输入职员姓名'],
			['job','require','请输入职员职务'],
			['motto'  ,'require','请输入职员格言'],
			['introduction'  ,'require','请输入职员简介']
		]);
		if($validate->check($data))
		{
			$file = request()->file('headimg');
			if($file)
			{
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/headimg');
				if($info)
				{
				// 成功上传后 获取上传信息
					$headimg='public/headimg/'.date('Ymd',time()).'/'.$info->getFilename();
					if ($headimg!==$dd)
					{
						$data['headimg']=$headimg;
						unlink($dd);
					}
				}
				else
				{
				// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}
			$re=$m->where('id', $ii['id'])->update($data);;
			if ($re) 
			{
				$this->success('修改成功,不要忘记修改英文版哦0.0','dolist', '', 1);
			}
			return $re;
		}
		else
		{
			$this->error($validate->getError());
		}
	}
	public function doadd()
	{
		$m= new M;
		$ii=input('post.');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'name'=>$ii['name'],
			'job'=>$ii['job'],
			'motto'=>$ii['motto'],
			'introduction'=>$ii['introduction'],
			'is_see'=>$ii['is_see']
			
			];
		$validate = new Validate([
			['name'  ,'require','请输入职员姓名'],
			['job'  ,'require','请输入职员职务'],
			['motto'  ,'require','请输入职员格言'],
			['introduction'  ,'require','请输入职员简介']
		]);
		if ($validate->check($data)) {
			$file = request()->file('headimg');
			// 移动到框架应用根目录/public/uploads/ 目录下
			if ($file) {
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/headimg');
				if($info){
				// 成功上传后 获取上传信息
					$headimg='public/headimg/'.date('Ymd',time()).'/'.$info->getFilename();
					$data['headimg']=$headimg;
				}else{
				// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}else{
				$this->error('请上传图片');
			}
				//添加 新闻
			$re=$m->save($data);
			if ($re) {
				$this->success('添加成功,不要忘记添加英文版哦0.0','dolist', '', 1);
			}
			return $re;
		}else
		{
			$this->error($validate->getError(),'add');
		}

	}

	//英文版
	public function doenlist()
	{
		$m=new MM;
		$data=$m->select();
		$this->assign("data",$data);
		return  $this->fetch('doenlist');
	}
	public function enedit()
	{
		$m=new MM;
		$ii=input('param.');
		$data=$m->where('id',$ii['id'])->find();
		$this->assign("data",$data);
		return $this->fetch('enedit');
	}
	
	public function endelete()
	{
		$m= new MM;
		$ii=input('param.');
		$data=$m->where('id',$ii['id'])->delete();
		if ($data) {
			$this->success('删除成功,不要忘记删除中文版哦0.0','doenlist', '', 1);
		}
		
	}
	public function enadd()
	{
		return $this->fetch('enadd');
	}
	public function endoedit()
	{
		$m= new MM;
		$ii=input('post.');
		$dd=$m->where('id',$ii['id'])->value('e_headimg');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'e_name'=>$ii['e_name'],
			'e_job'=>$ii['e_job'],
			'e_motto'=>$ii['e_motto'],
			'e_introduction'=>$ii['e_introduction'],
			'e_is_see'=>$ii['e_is_see']
			];

		$validate = new Validate([
			['e_name'  ,'require','请输入职员姓名'],
			['e_job','require','请输入职员职务'],
			['e_motto'  ,'require','请输入职员格言'],
			['e_introduction'  ,'require','请输入职员简介']
		]);
		if($validate->check($data))
		{
			$file = request()->file('e_headimg');
			if($file)
			{
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/headimg');
				if($info)
				{
				// 成功上传后 获取上传信息
					$e_headimg='public/headimg/'.date('Ymd',time()).'/'.$info->getFilename();
					if ($e_headimg!==$dd)
					{
						$data['e_headimg']=$e_headimg;
						unlink($dd);
					}
				}
				else
				{
				// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}
			$re=$m->where('id', $ii['id'])->update($data);;
			if ($re) 
			{
				$this->success('修改成功,不要忘记修改中文版哦0.0','doenlist', '', 1);
			}
			return $re;
		}
		else
		{
			$this->error($validate->getError());
		}
	}
	public function endoadd()
	{
		$m= new MM;
		$ii=input('post.');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'e_name'=>$ii['e_name'],
			'e_job'=>$ii['e_job'],
			'e_motto'=>$ii['e_motto'],
			'e_introduction'=>$ii['e_introduction'],
			'e_is_see'=>$ii['e_is_see']
			];
		$validate = new Validate([
			['e_name'  ,'require','请输入职员姓名'],
			['e_job','require','请输入职员职务'],
			['e_motto'  ,'require','请输入职员格言'],
			['e_introduction'  ,'require','请输入职员简介']
		]);
		if ($validate->check($data)) {
			$file = request()->file('e_headimg');
			// 移动到框架应用根目录/public/uploads/ 目录下
			if ($file) {
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/headimg');
				if($info){
				// 成功上传后 获取上传信息
					$e_headimg='public/headimg/'.date('Ymd',time()).'/'.$info->getFilename();
					$data['e_headimg']=$e_headimg;
				}else{
				// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}else{
				$this->error('请上传图片');
			}
				//添加 新闻
			$re=$m->save($data);
			if ($re) {
				$this->success('添加成功,不要忘记添加中文版哦0.0','doenlist', '', 1);
			}
			return $re;
		}else
		{
			$this->error($validate->getError());
		}

	}
}

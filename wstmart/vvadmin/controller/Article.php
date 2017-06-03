<?php
namespace  wstmart\vvadmin\Controller;
use wstmart\vvadmin\model\VvoffArticle as M;
use wstmart\vvadmin\model\VvoffEnarticle as MM;
use  think\Validate;
use  think\Request;
use  think\Controller;
class  Article  extends Controller
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
		$dd=$m->where('id',$ii['id'])->value('art_img');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'art_name'=>$ii['art_name'],
			'art_content'=>$ii['art_content'],
			'art_author'=>$ii['art_author'],
			'art_from'=>$ii['art_from'],
			'is_see'=>$ii['is_see']
			];

		$validate = new Validate([
			['art_name'  ,'require|max:60','请输入文章标题|文章标题不能超过20个字符'],
			['art_content'  ,'require','请输入文章内容'],
			['art_author'  ,'require','请输入文章作者'],
			['art_from'  ,'require','请输入文章来源']
		]);
		if($validate->check($data))
		{
			$file = request()->file('art_img');
			if($file)
			{
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/uploads');
				if($info)
				{
				// 成功上传后 获取上传信息
					$art_img='public/uploads/'.date('Ymd',time()).'/'.$info->getFilename();
					if ($art_img!==$dd)
					{
						$data['art_img']=$art_img;
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
			'art_name'=>$ii['art_name'],
			'art_content'=>$ii['art_content'],
			'art_author'=>$ii['art_author'],
			'art_from'=>$ii['art_from'],
			'is_see'=>$ii['is_see']
			
			];

		$validate = new Validate([
			['art_name'  ,'require|max:60','请输入文章标题|文章标题不能超过20个字符'],
			['art_content'  ,'require','请输入文章内容'],
			['art_author'  ,'require','请输入文章作者'],
			['art_from'  ,'require','请输入文章来源']
		]);
		if ($validate->check($data)) {
			$file = request()->file('art_img');
			// 移动到框架应用根目录/public/uploads/ 目录下
			if ($file) {
					# code...
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/uploads');
				if($info){
				// 成功上传后 获取上传信息
					$art_img='public/uploads/'.date('Ymd',time()).'/'.$info->getFilename();
					$data['art_img']=$art_img;
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
				$this->success('添加成功，不要忘记添加英文版哦0.0','dolist', '', 1);
			}
			return $re;
		}else
		{
			$this->error($validate->getError(),'add');
		}

	}

	//英文版
	public function enadd()
	{
		return $this->fetch('enadd');
	}
	public function doenlist()
	{
		$m=new MM;
		$data=$m->select();
		$this->assign("data",$data);
		return  $this->fetch('doenlist');
	}
	public function doenadd()
	{
		$m= new MM;
		$ii=input('post.');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'e_art_name'=>$ii['e_art_name'],
			'e_art_content'=>$ii['e_art_content'],
			'e_art_author'=>$ii['e_art_author'],
			'e_art_from'=>$ii['e_art_from'],
			'e_is_see'=>$ii['e_is_see']
			
			];

		$validate = new Validate([
			['e_art_name'  ,'require','请输入文章标题'],
			['e_art_content'  ,'require','请输入文章内容'],
			['e_art_author'  ,'require','请输入文章作者'],
			['e_art_from'  ,'require','请输入文章来源']
		]);
		if ($validate->check($data)) {
			$file = request()->file('e_art_img');
			// 移动到框架应用根目录/public/uploads/ 目录下
			if ($file) {
					# code...
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/uploads');
				if($info){
				// 成功上传后 获取上传信息
					$e_art_img='public/uploads/'.date('Ymd',time()).'/'.$info->getFilename();
					$data['e_art_img']=$e_art_img;
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
				$this->success('添加成功，不要忘记添加中文版哦0.0','doenlist', '', 1);
			}
			return $re;
		}else
		{
			$this->error($validate->getError(),'enadd');
		}

	}
	public function enedit()
	{
		$m=new MM;
		$ii=input('param.');
		$data=$m->where('id',$ii['id'])->find();
		$this->assign("data",$data);
		return $this->fetch('enedit');
	}
	public function endoedit()
	{
		$m= new MM;
		$ii=input('post.');
		$dd=$m->where('id',$ii['id'])->value('e_art_img');
		// 获取表单上传文件 例如上传了001.jpg
		$data=[
			'e_art_name'=>$ii['e_art_name'],
			'e_art_content'=>$ii['e_art_content'],
			'e_art_author'=>$ii['e_art_author'],
			'e_art_from'=>$ii['e_art_from'],
			'e_is_see'=>$ii['e_is_see']
			];

		$validate = new Validate([
			['e_art_name'  ,'require','请输入文章标题'],
			['e_art_content'  ,'require','请输入文章内容'],
			['e_art_author'  ,'require','请输入文章作者'],
			['e_art_from'  ,'require','请输入文章来源']
		]);
		if($validate->check($data))
		{
			$file = request()->file('e_art_img');
			if($file)
			{
				$info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move( './public/uploads');
				if($info)
				{
				// 成功上传后 获取上传信息
					$e_art_img='public/uploads/'.date('Ymd',time()).'/'.$info->getFilename();
					if ($e_art_img!==$dd)
					{
						$data['e_art_img']=$e_art_img;
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
	public function endelete()
	{
		$m= new MM;
		$ii=input('param.');
		$data=$m->where('id',$ii['id'])->delete();
		if ($data) {
			$this->success('删除成功，不要忘记删除中文版哦0.0','doenlist', '', 1);
		}
	}
}

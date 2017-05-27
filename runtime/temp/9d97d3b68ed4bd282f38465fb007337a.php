<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"/mnt/data/www/wstmart/vvadmin/view/article/enadd.html";i:1495100272;s:51:"/mnt/data/www/wstmart/vvadmin/view/index/index.html";i:1495716001;}*/ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<!-- Title and other stuffs -->
		<title>VV后台管理系统</title>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="">
		<link rel="icon" href="__VVADMIN__/img/1.ico" type="image/x-icon">
		<link href="__VVADMIN__/css/bootstrap.css" rel="stylesheet">
		<link href="__VVADMIN__/css/font-awesome.css" rel="stylesheet">
		<link href="__VVADMIN__/css/style.css" rel="stylesheet">
		<link href="__VVADMIN__/img/favicon/favicon.png" rel="shortcut icon">
	</head>
	<body>
		<!-- Header starts -->
		<header>
			<div class="container">
				<div class="row">

					<!-- Logo section -->
					<div class="col-md-4">
						<!-- Logo. -->
						<div class="logo">
							<h1><a href="#">vv官方后台管理平台<span class="bold"></span></a></h1>
							<p class="meta">VV Operation Management Platform</p>
						</div>
						<!-- Logo ends -->
					</div>
					<ul class="nav navbar-nav pull-right">
						<li class="dropdown pull-right">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="icon-user"></i><?php echo \think\Session::get('loginuser')['loginName']; ?><b class="caret"></b>
							</a>
							<!-- Dropdown menu -->
							<ul class="dropdown-menu">
								<li><a href="#"><i class="icon-user"></i> 查看资料</a>
								</li>
								<li><a href="#"><i class="icon-cogs"></i> 修改密码</a>
								</li>
								<li><a href="<?php echo url('Login/loginout')?>"><i class="icon-off"></i> 退出</a>
								</li>
							</ul>
						</li>

					</ul>
				</div>
			</div>
		</header>

		<!-- Header ends -->

		<!-- Main content starts -->

		<div class="content">

			<!-- Sidebar -->
			<div class="sidebar">
				<div class="sidebar-dropdown"><a href="#">导航</a>
				</div>
				<ul id="nav">
					
					<li class="has_sub"><a href="#" class="open"><i class="icon-list-alt"></i> 职员管理 <span class="pull-right"><i class="icon-chevron-right"></i></span></a>
						<ul>
							<li><a href="<?php echo url('Staff/dolist')?>">职员信息中文版</a>
							</li>
							<li><a href="<?php echo url('Staff/doenlist')?>">职员信息英文版</a>
							</li>
							<li><a href="<?php echo url('Staff/add')?>">职员添加中文版</a>
							</li>
							<li><a href="<?php echo url('Staff/enadd')?>">职员添加英文版</a>
							</li>						
						</ul>
					</li>
					<li class="has_sub"><a href="#"><i class="icon-calendar"></i> 官方新闻<span class="pull-right"><i class="icon-chevron-right"></i></span></a>
						<ul>
							<li><a href="<?php echo url('Article/dolist')?>">新闻列表中文版</a>
							</li>
							<li><a href="<?php echo url('Article/doenlist')?>">新闻列表英文版</a>
							</li>
							<li><a href="<?php echo url('Article/add')?>">新闻添加中文版</a>
							</li>
							<li><a href="<?php echo url('Article/enadd')?>">新闻添加英文版</a>
							</li>
						</ul>
					</li>
					<li class="has_sub"><a href="#"><i class="icon-calendar"></i> 系统管理<span class="pull-right"><i class="icon-chevron-right"></i></span></a>
						<ul>
							<li><a href="#">用户管理</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>

			<!-- Sidebar ends -->
			<!--在父模板中定义一个Workspace块,用于子模板中同名的块的替换-->
							
<div class="mainbar">

				<!-- Page heading -->
				<div class="page-head">
					<h2 class="pull-left"><i class="icon-list-alt"></i>文章添加(英文版)</h2>

					<!-- Breadcrumb -->
					<div class="bread-crumb pull-right">
						<a href="<?php echo url('Article/doenlist'); ?>"><i class="icon-list-alt"></i>文章列表(英文版)</a>
						<!-- Divider -->
						<span class="divider">/</span>
						<a href="#" class="bread-current">文章添加(英文版)</a>
					</div>

					<div class="clearfix"></div>

				</div>
				<!-- Page heading ends -->

				<!-- Matter -->
		          
	    <div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head" style="padding: 8px 15px;">
                  <div class="pull-left">文章添加(英文版)</div>
                 
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">

                    <!-- Form starts.  -->
                     <form class="form-horizontal"  action="<?php echo url('Article/doenadd'); ?>" method="post" id="enartadd" enctype="multipart/form-data">

                                <div class="form-group">
                                  <label class="col-lg-4 control-label">文章标题</label>
                                  <div class="col-lg-8">
                                    <input class="form-control" placeholder="" type="text" name="e_art_name">
                                  </div>
                                </div>                               
                                <div class="form-group">
                                  <label class="col-lg-4 control-label">文章内容</label>
                                  <div class="col-lg-8">
                                    <textarea  name="e_art_content" id="art_content" cols="132" rows="10">
                                    	
                                    </textarea>
                                  </div>
                                 </div>
                                 <div class="form-group">
                                  <label class="col-lg-4 control-label">文章作者</label>
                                  <div class="col-lg-8">
                                    <input class="form-control" placeholder="" type="text" name="e_art_author">
                                  </div>
                                 </div>
                                 <div class="form-group">
                                  <label class="col-lg-4 control-label">文章来源</label>
                                  <div class="col-lg-8">
                                    <input class="form-control" placeholder="" type="text" name="e_art_from" >
                                  </div>
                                 </div>
                                 <div class="form-group">
                                  <label class="col-lg-4 control-label">是否显示</label>
                                  <div class="col-lg-8">
                                   <input name="e_is_see" type="radio" value="1" checked="checked" />是
                                    <input name="e_is_see" type="radio" value="0" />否
                                  </div>
                                 </div>
                                <div class="form-group">
                                  <label class="col-lg-4 control-label">文章图片</label>
                                  <div class="col-lg-6">
                                    <input class="form-control" type="file" name="e_art_img">
                                  </div>
			<div class="col-lg-2">
                                    <img src="" id="showImg" style="display:none" width="70">
                                  </div>
                                </div>        
                                <div class="form-group">
                                  <div class="col-lg-offset-1 col-lg-9">
                            
                                    <input type="submit" value="提交" class="btn btn-primary">
                                  
                                    <button type="button" onclick="history.go(-1);" class="btn btn-danger">关闭</button>
                                  </div>
                                </div>
                             </form>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>

          </div>

        </div>
		  </div>
				</div>


			<!-- Mainbar ends -->
			<div class="clearfix"></div>

		</div>
		<!-- Content ends -->


		<span class="totop"><a href="#"><i class="icon-chevron-up"></i></a></span>
		<script src="__VVADMIN__/js/jquery-1.8.3.min.js"></script>	
		<script src="__VVADMIN__/js/bootstrap.js"></script>	
		<script src="__VVADMIN__/js/custom.js"></script>
	</body>
</html>

<block name='js'>
</block>

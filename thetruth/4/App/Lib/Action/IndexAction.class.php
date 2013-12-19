<?php
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
class IndexAction extends Action
{

    public function index() {
        session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
		$model2=M("user");
		$user['username']=$usrname;
		$search = $model2->where($user)->select();
		if(!$search)
		{
            $this->error('欢迎您使用本应用，首次使用请完善相关信息',U('Index/login'));
		}
		$model=M('info');
		$list=$model->order('create_time desc')->select();
        if(!$usrname){$this->error('系统查找不到该操作,请重试!');}
		$this->assign('infolist',$list);
		$this->assign('usrname',$usrname);
		$this->display();
    }
    public function login_cs()
    {
        session_start();
		error_reporting(1);
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
        //$checkbox=$_REQUEST["label"][0];
        //$checkbox1=$_REQUEST["label"][1];
        //echo $checkbox[0];
        //echo $checkbox;
        //echo $checkbox1;
        $model=D("user");
        $mydata['username']=$usrname;
        $mydata['level']=0;
        $mydata['power']=0;
      	if(!empty($_POST["label"]))
        {
            for($i=0; $i<count($_POST["label"]); $i++)
            {
                $interest=$_POST["label"][$i];
                if($interest==1)
                {
                    $mydata['dzsm']=9999;
                }
                else if($interest==2)
                {
                    $mydata['yljk']=9999;
                }
                else if($interest==3)
                {
                    $mydata['gjxw']=9999;
                }
                else if($interest==4)
                {
                    $mydata['shms']=9999;
                }
                else if($interest==5)
                {
                    $mydata['game']=9999;
                }
                else if($interest==6)
                {
                    $mydata['jykx']=9999;
                }
                else if($interest==7)
                {
                    $mydata['lysj']=9999;
                }
                else if($interest==8)
                {
                    $mydata['whys']=9999;
                }
                else if($interest==9)
                {
                    $mydata['sylc']=9999;
                }
                else if($interest==10)
                {
                    $mydata['qt']=9999;
                }
      		 }
        }
        if(!$mydata)
        {
            $this->error('获取用户信息失败');
        }
        else
        {
            $result	=$model->add($mydata);
            if($result)
			{
                $this->success('用户信息获取成功',U('Index/index'));
			}
			else
			{
                $this->error('获取用户信息失败');
          	}
        }
        /*$yy=count($_POST["label"]);
        echo $yy;
        if(!empty($_POST["label"]))
        {
            for($i=0; $i<count($_POST["label"]); $i++)
            {
                $interest=$_POST["label"][$i];
                $mydata2['interest']=$interest;
               	$result2=$modelm->add($mydata2);
                echo $result2;
                echo $mydata2['username'];
                echo $mydata2['interest'];
              	if($result2)
                {
                    echo 123123123123;
                    $QQ++;
                }
      		 }
        }
        $data['username']=$usrname;
        $data['interest']='lala';
        $result3=$model2->add($data);
        echo "<br>";
        echo $data['username'];
        echo $data['interest'];
        if($result3)
        {
              echo 2222222222222;
              $QQ++;
         }*/
		/*if(!$checkbox)
		{
            $this->error('未能提交成功',U('Index/login'));
        }
		if($checkbox)
		{
            $this->success('提交成功',U('Index/index'));
        }*/
        /*echo $QQ;
        if($QQ==count($_POST["label"]))
        {
             $this->success('提交成功',U('Index/index'));
        }
        else
        {
             $this->error('未能提交成功',U('Index/login'));
        }*/
    }
    public function login()
	{
		session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
        $this->assign('usrname',$usrname);
        $this->display();
    }
    public function insearch()
    {
        session_start();
		error_reporting(1);
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
		$modelinfo=M("info");
		$modeluser=M("user");
		$searchuser['username']=$usrname;
		$user=$modeluser->where($searchuser)->select();
            //echo $user;
            //echo $searchuser['username'];
            //echo $usrname;

            //echo $user[0]['username'];
            //echo $user[0]['game'];
        if($user[0]['dzsm']==9999)
        {
            $searchinfo1['label']="电子数码";
            $search1 = $modelinfo->where($searchinfo1)->select();
            $this->assign('thetruth',$search1);
        }
        if($user[0]['yljk']==9999)
        {
            $searchinfo2['label']="医疗健康";
            $search2 = $modelinfo->where($searchinfo2)->select();
            $this->assign('thetruth',$search2);
        }
        if($user[0]['gjxw']==9999)
        {
            $searchinfo3['label']="国际新闻";
            $search3 = $modelinfo->where($searchinfo3)->select();
            $this->assign('thetruth',$search3);
        }
        if($user[0]['shms']==9999)
        {
            $searchinfo4['label']="社会民生";
            $search4 = $modelinfo->where($searchinfo4)->select();
            $this->assign('thetruth',$search4);
        }
        if($user[0]['game']==9999)
        {
            $searchinfo5['label']="游戏";
            $search5 = $modelinfo->where($searchinfo5)->select();
            $this->assign('thetruth',$search5);
        }
        if($user[0]['jykx']==9999)
        {
            $searchinfo6['label']="教育科学";
            $search6 = $modelinfo->where($searchinfo6)->select();
            $this->assign('thetruth',$search6);
        }
        if($user[0]['lysj']==9999)
        {
            $searchinfo7['label']="灵异事件";
            $search7 = $modelinfo->where($searchinfo7)->select();
            $this->assign('thetruth',$search7);
        }
        if($user[0]['whys']==9999)
        {
            $searchinfo8['label']="文化艺术";
            $search8 = $modelinfo->where($searchinfo8)->select();
            $this->assign('thetruth',$search8);
        }
        if($user[0]['sylc']==9999)
        {
            $searchinfo9['label']="商业理财";
            $search9 = $modelinfo->where($searchinfo9)->select();
            $this->assign('thetruth',$search9);
        }
        if($user[0]['qt']==9999)
        {
            $searchinfo10['label']="其他";
            $search10 = $modelinfo->where($searchinfo10)->select();
            $this->assign('thetruth',$search10);
        }
        //$searall[1]=1
        $se=0;
        for($i=0;$i<count($search1);$i++)
        {
            $searall[$se]=$search1[$i];
            //echo $search1[$i]['content'];
            //echo 1;
            $se++;
        }
        for($i=0;$i<count($search2);$i++)
        {
            $searall[$se]=$search2[$i];
            //echo $search2[$i]['content'];
            //echo 2;
            $se++;
        }
        for($i=0;$i<count($search3);$i++)
        {
            $searall[$se]=$search3[$i];
            //echo $search3[$i]['content'];
            //echo 3;
            $se++;
        }
        for($i=0;$i<count($search4);$i++)
        {
            $searall[$se]=$search4[$i];
            //echo $search4[$i]['content'];
            //echo 4;
            $se++;
        }for($i=0;$i<count($search5);$i++)
        {
            $searall[$se]=$search5[$i];
            //echo $search5[$i]['content'];
            //echo 5;
            $se++;
        }for($i=0;$i<count($search6);$i++)
        {
            $searall[$se]=$search6[$i];
            //echo $search6[$i]['content'];
            //echo 6;
            $se++;
        }for($i=0;$i<count($search7);$i++)
        {
            $searall[$se]=$search7[$i];
            //echo $search7[$i]['content'];
            //echo 7;
            $se++;
        }for($i=0;$i<count($search8);$i++)
        {
            $searall[$se]=$search8[$i];
            //echo $search8[$i]['content'];
            //echo 8;
            $se++;
        }for($i=0;$i<count($search9);$i++)
        {
            $searall[$se]=$search9[$i];
            //echo $search9[$i]['content'];
            //echo 9;
            $se++;
        }for($i=0;$i<count($search10);$i++)
        {
            $searall[$se]=$search10[$i];
            //echo $search10[$i]['content'];
            //echo 10;
            $se++;
        }
       // $search['label']=array($searchinfo1['label'],$searchinfo5['label'],'or');
        //$search=$modelinfo->where($searchinfo1)or($searchinfo2)or($searchinfo3)or($searchinfo4)or($searchinfo5)or($searchinfo6)or($searchinfo7)or($searchinfo8)or($searchinfo9)or($searchinfo10)->select();
        $this->assign('thetruth',$searall);
        $this->display();
    }
	public function search()
	{
		session_start();
		error_reporting(1);
		$text = $_REQUEST['thetruth'];
		//if(!$text){$this->error('系统查找不到该操作,请重试!');}
		$text1= $_POST['label'];
		echo $text1;
		$model=M("info");
		$model2=M("info");
        $condition['content']=array('like','%'.$text.'%');
        if(!$text1)
        {
            $search = $model->where($condition)->select();
            if(!$search){$this->error('你要搜索的真相不存在，请新建！');}
            $this->assign('thetruth',$search);
        }
        else
        {
            $condition['label']=$text1;
            $search=$model2->where($condition)->select();
            if(!$search){$this->error('你要搜索的真相不存在，请新建！');}
            $this->assign('thetruth',$search);
        }
		$this->display();
	}
	public function submit()
	{
		session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$text = $_REQUEST['thetruth'];
		if(!$text){$this->error('系统查找不到该操作,请重试!');}
		$model=D("info");
		$mydata['content']=$text;
		$mydata['author_userid']=$user_message['screen_name'];
		$mydata['state']=0;
        $mydata['create_time']=time();
        $text1= $_POST['label'];
        //echo $text1;
        //if(!$text1){$this->error('请为您的待求真相选择一个标签!');}
        if(!$text1){$mydata['label']='其他';}
		else{$mydata['label']=$text1;}
		$mydata['z']=0;
		$mydata['f']=0;
		if($mydata)
		{
			$result	=$model->add($mydata);
			if($result)
			{
                //echo time();
                //echo 123123123;
				$this->success('提交成功');
			}
			else{$this->error('提交失败');}
		}
		else
		{
			$this->error('提交失败');
		}

	}
	public function top()
	{
		session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
		$model=M('info');
		$list=$model->select();
		if(!$usrname){$this->error('系统查找不到该操作,请重试!');}
		$this->assign('infolist',$list);
		$this->assign('usrname',$usrname);
		$this->display();
	}
	public function answerlist()
	{
		session_start();
		error_reporting(1);
		$id = $_REQUEST['id'];
		if(!$id){$this->error('系统查找不到该操作,请重试!');}
		$model=M("ans");
		$model2=M("info");
		$list=$model->where("info_id=$id")->select();
		$info=$model2->where("id=$id")->find();
		$this->assign('info',$info);
		$this->assign('anslist',$list);
		$this->display();
	}
	public function submitans()
	{
		session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$zof=$_POST['zof'];
		$text=$_POST['thetruth'];
		$id = $_REQUEST['id'];
		$user=$user_message['screen_name'];
		if(!$zof||!$uid){$this->error('系统查找不到该操作,请重试!');}
		$modelfind=M("ans");
        $modelb=M("info");
        $not=$modelb->where("id=$id")->find();
            if($not['state']!="0")
                $this->error("已经结束");
		$done=$modelfind->where("info_id=$id and answer_id='$user'")->find();
		if($done)
		{
			$this->error('对这个提问你已经发表过见解');
		}
		else
		{
            $now = date("Ymd_His");
			$code = $now."_".mt_rand(10000, 99999);
          //  if(!$_POST['mytag'])
          //    	$this->showmsg_box('KOng!',"",0,5);
          	if($_FILES)
            {
		//如果有文件上传 上传附件
                  // import("@.ORG.UploadFile");
                $signal=true;
                    import("ORG.Net.UploadFile");
                $upload = new UploadFile();
                 $upload->maxSize = 3292200;
                //设置上传文件类型
                $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
                //设置附件上传目录
                          $upload->savePath = './Public/Uploads/Photo/';
                //设置需要生成缩略图，仅对图像文件有效
                $upload->thumb = true;
                // 设置引用图片类库包路径
                $upload->imageClassPath = '@.ORG.Image';
                //设置需要生成缩略图的文件后缀
                          //  $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
                           $upload->thumbPrefix   =  'b_';  //生产3张缩略图
                //设置缩略图最大宽度
                $upload->thumbMaxWidth = '560';
                //设置缩略图最大高度
                $upload->thumbMaxHeight = '560';
                //设置上传文件规则
                $upload->saveRule = $code;
                //删除原图
                $upload->thumbRemoveOrigin = true;
                if (!$upload->upload())
                {
                    //捕获上传异常
                    if($upload->getErrorMsg()!="没有选择上传文件")
                    $this->error($upload->getErrorMsg());
                }
                else
                {
                    //取得成功上传的文件信息
                    $uploadList = $upload->getUploadFileInfo();
                    import("@.ORG.Image");
                    //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
                  // Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], '../Public/Images/logo2.png');
                  // $_POST['image'] = $uploadList[0]['savename'];
                }
            }







			$model=D("ans");
                if($signal)
            $mydata['photo']=$uploadList[0]['savename'];
			$mydata['answer_id']=$user;
			$mydata['content']=$text;
			$mydata['info_id']=$id;

			if($zof=='z')
			{
				$mydata['zorf']=1;
			}
			else
			{
				$mydata['zorf']=0;
			}
			if($mydata)
			{
				$result	=$model->add($mydata);
				if($result)
				{
                    $modeljudge=$modelb->where("id=$id")->find();
					if($mydata['zorf'])
					{
						$result2=$modelb->where("id=$id")->setInc("z",1);
                        $modeljudge['z']++;
					}
					else
					{
						$result2=$modelb->where("id=$id")->setInc("f",1);
                        $modeljudge['f']++;
					}


                    if($result2)
                    {

                    	$modelc=M("info");
                        echo $modeljudge['z'];
                        echo "nimeimei";
                        echo $modeljudge['f'];
                        if( $modeljudge['z'] + $modeljudge['f'] >= $modeljudge['actor'] &&  time() > $modeljudge['time'] + $modeljudge['create_time'])
                        {
                            if($modeljudge['z'] >= 3*$modeljudge['f'])
                            {
                                 $modelc->where("id=$id")->setField("state",1);
                            }
                            else if($modeljudge['f']>=3*$modeljudge['z'])
                            {
                                 $modelc->where("id=$id")->setField("state",2);
                            }
                            else
                            {
                                $modelc->where("id=$id")->setField("state",3);
                            }

                        }
                    }



					$this->success('提交成功');
				}
				else
				{
					$this->error('提交失败');
				}
			}
			else
			{
				$this->error('提交失败');
			}
		}

	}







	public function upanddown()
	{
		session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$id=$_REQUEST['ans_id'];
		$tag=$_REQUEST['tag'];
		$userid=$user_message['screen_name'];
		if(!$id||!$userid||!$tag)
		{
			$this->error('系统查找不到该操作,请重试!');
		}
		$modelfind=M("upanddown");
		$done=$modelfind->where("ans_id=$id and user_id='$userid'")->find();
		if($done)
		{
			$this->error('对同一证据只能(顶/踩)一次');
		}
		$modelb=M("ans");
		$model=D("upanddown");
		$mydata['ans_id']=$id;
		$mydata['user_id']=$userid;
		if($mydata)
		{
			$result	=$model->add($mydata);
			if($result)
			{
				if($tag=='z')
				{
					$modelb->where("id=$id")->setInc("up",1);
				}
				else
				{
					$modelb->where("id=$id")->setInc("down",1);
				}
				$this->success('操作成功');
			}
			else
			{
				$this->error('操作成功');
			}
		}
		else
		{
			$this->error('操作成功');
		}
	}


}
?>


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

        if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }



		$model2=M("user");
		$user['username']=$usrname;
		$search = $model2->where($user)->select();
		if(!$search)
		{
            $this->error('欢迎您使用本应用，首次使用请完善相关信息',U('Index/login'));
		}
        import ( '@.ORG.Page' );
		$model=M('info');
        $count=$model->count();
        $Page = new Page($count,15);
        $show = $Page->show();
		$list=$model->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $myflag=0;
        $time=time();
        while($list[$myflag])
        {
            $mytemp=$list[$myflag]['create_time']+$list[$myflag]['time']-$time;
            if($mytemp<=0)
            {
                 $list[$myflag]['end_time']="时间截止,人数不足";

                 if($list[$myflag]['z']+$list[$myflag]['f']>=$list[$myflag]['actor'])
                 {
                     $modelc=M("info");
                     $modeld=M("ans");
                     $modeluser=M("user");
                     if($list[$myflag]['z']>2.9*$list[$myflag]['f'])
                     {
                         $modelc->where("id=$list[$myflag]['id']")->setField("state",1);
                         $list[$myflag]['state']=1;
                         
                         $asker=$modelc->where("id=$list[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("bl",1);
                         $ansuser=$modeld->where("info_id=$list[$myflag]['id'] and zorf=1")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                     }
                     else if($list[$myflag]['f']>2.9*$list[$myflag]['z'])
                     {
                         $modelc->where("id=$list[$myflag]['id']")->setField("state",2);
                         $list[$myflag]['state']=2;
                         
                         $asker=$modelc->where("id=$list[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("zy",1);
                         $ansuser=$modeld->where("info_id=$list[$myflag]['id'] and zorf=0")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                         }
                         
                     }
                     else
                     {
                         $modelc->where("id=$list[$myflag]['id']")->setField("state",3);
                         $list[$myflag]['state']=3;
                     }

                 }

            }
            else
            {
                $day=floor($mytemp/86400);
                $mytemp=$mytemp-$day*86400;
                $hour=floor($mytemp/3600);
                $mytemp=$mytemp-$hour*3600;

                $minute=floor($mytemp/60);
                $second=$mytemp-$minute*60;
                $end_time=NULL;
                if($day)
                {
                    $end_time=$end_time.$day."天";
                }
                if($hour)
                {
                    $end_time=$end_time.$hour."小时";
                }
                if($minute)
                {
                    $end_time=$end_time.$minute."分";
                }
                if($second)
                {
                    $end_time=$end_time.$second."秒";
                }

                $list[$myflag]['end_time']=$end_time;
            }
            $myflag++;
        }
        
        
        
        
        
        
        
        $this->assign('page',$show);
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
        $model=D("user");
        $mydata['username']=$usrname;
        $condition['username']=$usrname;
        $resultname=$model->where($condition)->select();
        if($resultname)
        {
            $mydata['dzsm']=0;
            $mydata['yljk']=0;
            $mydata['gjxw']=0;
            $mydata['shms']=0;
            $mydata['game']=0;
            $mydata['jykx']=0;
            $mydata['lysj']=0;
            $mydata['whys']=0;
            $mydata['sylc']=0;
            $mydata['qt']=0;
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
            $resultsave=$model->where($condition)->save($mydata);
            if($resultsave)
            {
                $this->success('修改成功，正在返回大厅',U('Index/index'));
            }
            else
            {
                $this->success('修改成功，正在返回大厅',U('Index/index'));
            }
        }
        else
        {
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
                if(!$mydata['username'])
                {
                    $this->error('获取用户信息失败');
                }
                $result	=$model->add($mydata);
                if($result)
                {
                    $this->success('用户信息获取成功',U('Index/index'));
                }
                else
                {
                    $this->error('加入用户信息失败');
                }
            }
        }
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
        if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
		$model=D("user");
		$mydata['username']=$usrname;
		$condition['username']=$usrname;
		if($usrname)
		{
            $result=$model->where($condition)->select();
            if($result)
            {
                if($result[0]['dzsm']==9999){$interest[0]="电子数码 ";}
                if($result[0]['yljk']==9999){$interest[1]="医疗健康 ";}
                if($result[0]['gjxw']==9999){$interest[2]="国际新闻 ";}
                if($result[0]['shms']==9999){$interest[3]="社会民生 ";}
                if($result[0]['game']==9999){$interest[4]="游戏 ";}
                if($result[0]['jykx']==9999){$interest[5]="教育科学 ";}
                if($result[0]['lysj']==9999){$interest[6]="灵异事件 ";}
                if($result[0]['whys']==9999){$interest[7]="文化艺术 ";}
                if($result[0]['sylc']==9999){$interest[8]="商业理财 ";}
                if($result[0]['qt']==9999){$interest[9]="其他 ";}
                $this->assign('interest0',$interest[0]);
                $this->assign('interest1',$interest[1]);
                $this->assign('interest2',$interest[2]);
                $this->assign('interest3',$interest[3]);
                $this->assign('interest4',$interest[4]);
                $this->assign('interest5',$interest[5]);
                $this->assign('interest6',$interest[6]);
                $this->assign('interest7',$interest[7]);
                $this->assign('interest8',$interest[8]);
                $this->assign('interest9',$interest[9]);
            }
            else
            {
                $mydata['level']=0;
                $mydata['power']=0;
                $result	=$model->add($mydata);
            }
        }
        $this->assign('usrname',$usrname);
        $this->display();
    }
    public function changein()
    {
        session_start();
		error_reporting(1);
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
		$model=D("user");
        $mydata['username']=$usrname;
        if(!$usrname)
        {
            $this->error('请先登录');
        }
        else
        {
            $this->success('正在跳转页面，请稍候',U('Index/login'));

        }
    }
    public function myjoinsearch()
    {
        session_start();
		error_reporting(1);
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
		if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
        $modelans=M("ans");
        $modelinfo=M("info");
        $condition['answer_id']=$usrname;
        $search=$modelans->where($condition)->select();
        $num=count($search);
        //echo $search[0]['info_id'];
        $key=0;
        for($i=0;$i<count($search);$i++)
        {
            $zj=$search[$i]['info_id'];
            if($key==0)
            {
                $searchkey="id=$zj";
                $key=1;
            }
            else
            {
                $searchkey=$searchkey." or id=$zj";
            }
        }
        import ( '@.ORG.Page' );
        $count=$modelinfo->where($searchkey)->count();
        $Page = new Page($count,15);
        $show = $Page->show();
        $searall = $modelinfo->where($searchkey)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $myflag=0;
        $time=time();
        while($searall[$myflag])
        {
            $mytemp=$searall[$myflag]['create_time']+$searall[$myflag]['time']-$time;
            if($mytemp<=0)
            {
                 $searall[$myflag]['end_time']="时间截止,人数不足";

                 if($searall[$myflag]['z']+$searall[$myflag]['f']>=$searall[$myflag]['actor'])
                 {
                     $modelc=M("info");
                     if($searall[$myflag]['z']>2.9*$searall[$myflag]['f'])
                     {
                         $modelc->where("id=$searall[$myflag]['id']")->setField("state",1);
                         $searall[$myflag]['state']=1;
                     }
                     else if($searall[$myflag]['f']>2.9*$searall[$myflag]['z'])
                     {
                         $modelc->where("id=$searall[$myflag]['id']")->setField("state",2);
                         $searall[$myflag]['state']=2;
                     }
                     else
                     {
                         $modelc->where("id=$searall[$myflag]['id']")->setField("state",3);
                         $searall[$myflag]['state']=3;
                     }
                 }
            }
            else
            {
                $day=floor($mytemp/86400);
                $mytemp=$mytemp-$day*86400;
                $hour=floor($mytemp/3600);
                $mytemp=$mytemp-$hour*3600;

                $minute=floor($mytemp/60);
                $second=$mytemp-$minute*60;
                $end_time=NULL;
                if($day)
                {
                    $end_time=$end_time.$day."天";
                }
                if($hour)
                {
                    $end_time=$end_time.$hour."小时";
                }
                if($minute)
                {
                    $end_time=$end_time.$minute."分";
                }
                if($second)
                {
                    $end_time=$end_time.$second."秒";
                }

                $searall[$myflag]['end_time']=$end_time;
            }
            $myflag++;
        }
        $this->assign('thetruth',$searall);
        $this->assign('page',$show);
        $this->display();
    }
    public function mysearch()
    {
        session_start();
		error_reporting(1);
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
		if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
		$model=D("info");
		$condition['author_userid']=$usrname;
		$count = $model->where($condition)->count();
        if(!$count){$this->error('你要搜索的真相不存在，请新建！');}
		import ( '@.ORG.Page' );
        $Page = new Page($count,15);
        $show = $Page->show();
		$search = $model->where($condition)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $myflag=0;
        $time=time();
        while($search[$myflag])
        {
            $mytemp=$search[$myflag]['create_time']+$search[$myflag]['time']-$time;
            if($mytemp<=0)
            {
                 $search[$myflag]['end_time']="时间截止,人数不足";

                 if($search[$myflag]['z']+$search[$myflag]['f']>=$search[$myflag]['actor'])
                 {
                     $modelc=M("info");
                     $modeld=M("ans");
                     $modeluser=M("user");
                     if($search[$myflag]['z']>2.9*$search[$myflag]['f'])
                     {
                         $modelc->where("id=$search[$myflag]['id']")->setField("state",1);
                         $search[$myflag]['state']=1;
                         
                         
                         $asker=$modelc->where("id=$search[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("bl",1);
                         $ansuser=$modeld->where("info_id=$search[$myflag]['id'] and zorf=1")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                     }
                     else if($search[$myflag]['f']>2.9*$search[$myflag]['z'])
                     {
                         $modelc->where("id=$search[$myflag]['id']")->setField("state",2);
                         $search[$myflag]['state']=2;
                         
                         
                         $asker=$modelc->where("id=$search[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("zy",1);
                         $ansuser=$modeld->where("info_id=$search[$myflag]['id'] and zorf=0")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                         
                     }
                     else
                     {
                         $modelc->where("id=$search[$myflag]['id']")->setField("state",3);
                         $search[$myflag]['state']=3;
                     }
                 }
            }
            else
            {
                $day=floor($mytemp/86400);
                $mytemp=$mytemp-$day*86400;
                $hour=floor($mytemp/3600);
                $mytemp=$mytemp-$hour*3600;

                $minute=floor($mytemp/60);
                $second=$mytemp-$minute*60;
                $end_time=NULL;
                if($day)
                {
                    $end_time=$end_time.$day."天";
                }
                if($hour)
                {
                    $end_time=$end_time.$hour."小时";
                }
                if($minute)
                {
                    $end_time=$end_time.$minute."分";
                }
                if($second)
                {
                    $end_time=$end_time.$second."秒";
                }

                $search[$myflag]['end_time']=$end_time;
            }
            $myflag++;
        }
        $this->assign('page',$show);
        $this->assign('thetruth',$search);
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
         if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
		$modelinfo=M("info");
		$modeluser=M("user");
		$searchuser['username']=$usrname;
		$user=$modeluser->where($searchuser)->select();
        $key=0;
        if($user[0]['dzsm']==9999)
        {
            if(!$key)
            {
           		 $searchkey="label='电子数码'";
                $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='电子数码'";
            }
        }
        if($user[0]['yljk']==9999)
        {
             if(!$key)
            {
           		 $searchkey="label='医疗健康'";
                 $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='医疗健康'";
            }
        }
        if($user[0]['gjxw']==9999)
        {
             if(!$key)
            {
           		 $searchkey="label='国际新闻'";
                 $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='国际新闻'";
            }
        }
        if($user[0]['shms']==9999)
        {
             if(!$key)
            {
           		 $searchkey="label='社会民生'";
                 $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='社会民生'";
            }
        }
        if($user[0]['game']==9999)
        {
             if(!$key)
            {
           		 $searchkey="label='游戏'";
                 $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='游戏'";
            }
        }
        if($user[0]['jykx']==9999)
        {
             if(!$key)
            {
           		 $searchkey="label='教育科学'";
                 $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='教育科学'";
            }
        }
        if($user[0]['lysj']==9999)
        {
            if(!$key)
            {
           		 $searchkey="label='灵异事件'";
                $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='灵异事件'";
            }
        }
        if($user[0]['whys']==9999)
        {
            if(!$key)
            {
           		 $searchkey="label='文化艺术'";
                $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='文化艺术'";
            }
        }
        if($user[0]['sylc']==9999)
        {
            if(!$key)
            {
           		 $searchkey="label='商业理财'";
                $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='商业理财'";
            }
        }
        if($user[0]['qt']==9999)
        {
            if(!$key)
            {
           		 $searchkey="label='其他'";
                $key=1;
            }
            else
            {
                $searchkey=$searchkey." or label='其他'";
            }
        }
        import ( '@.ORG.Page' );
        	$count=$modelinfo->where($searchkey)->count();
        	$Page = new Page($count,15);
        	$show = $Page->show();

        $searall = $modelinfo->where($searchkey)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$myflag=0;
        $time=time();
        while($searall[$myflag])
        {
            $mytemp=$searall[$myflag]['create_time']+$searall[$myflag]['time']-$time;
            if($mytemp<=0)
            {
                 $searall[$myflag]['end_time']="时间截止,人数不足";

                 if($searall[$myflag]['z']+$searall[$myflag]['f']>=$searall[$myflag]['actor'])
                 {
                     $modelc=M("info");
                     $modeld=M("ans");
                     $modeluser=M("user");
                     
                     
                     if($searall[$myflag]['z']>2.9*$searall[$myflag]['f'])
                     {
                         $modelc->where("id=$searall[$myflag]['id']")->setField("state",1);
                         $searall[$myflag]['state']=1;
                         
                         $asker=$modelc->where("id=$searall[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("bl",1);
                         $ansuser=$modeld->where("info_id=$searall[$myflag]['id'] and zorf=1")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                         
                         
                     }
                     else if($searall[$myflag]['f']>2.9*$searall[$myflag]['z'])
                     {
                         $modelc->where("id=$searall[$myflag]['id']")->setField("state",2);
                         $searall[$myflag]['state']=2;
                         
                         
                         $asker=$modelc->where("id=$searall[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("zy",1);
                         $ansuser=$modeld->where("info_id=$searall[$myflag]['id'] and zorf=0")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                         
                     }
                     else
                     {
                         $modelc->where("id=$searall[$myflag]['id']")->setField("state",3);
                         $searall[$myflag]['state']=3;
                     }

                 }

            }
            else
            {
                $day=floor($mytemp/86400);
                $mytemp=$mytemp-$day*86400;
                $hour=floor($mytemp/3600);
                $mytemp=$mytemp-$hour*3600;

                $minute=floor($mytemp/60);
                $second=$mytemp-$minute*60;
                $end_time=NULL;
                if($day)
                {
                    $end_time=$end_time.$day."天";
                }
                if($hour)
                {
                    $end_time=$end_time.$hour."小时";
                }
                if($minute)
                {
                    $end_time=$end_time.$minute."分";
                }
                if($second)
                {
                    $end_time=$end_time.$second."秒";
                }

                $searall[$myflag]['end_time']=$end_time;
            }
            $myflag++;
        }


        $this->assign('thetruth',$searall);
        $this->assign('page',$show);
        $this->display();
    }
	public function search()
	{
		session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
         if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
		$text = $_REQUEST['thetruth'];
		//if(!$text){$this->error('系统查找不到该操作,请重试!');}
		$text1= $_POST['label'];
		echo $text1;
		$model=M("info");
        //$model2=M("info");
        $condition['content']=array('like','%'.$text.'%');
        /*if(!$text1)
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
        }*/
        if($text1)
        {
            $condition['label']=$text1;
        }
        	$count = $model->where($condition)->count();
        if(!$count){$this->error('你要搜索的真相不存在，请新建！');}
        	import ( '@.ORG.Page' );
        	$Page = new Page($count,15);
        	$show = $Page->show();
            $search = $model->where($condition)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $myflag=0;
        $time=time();
        while($search[$myflag])
        {
            $mytemp=$search[$myflag]['create_time']+$search[$myflag]['time']-$time;
            if($mytemp<=0)
            {
                 $search[$myflag]['end_time']="时间截止,人数不足";

                 if($search[$myflag]['z']+$search[$myflag]['f']>=$search[$myflag]['actor'])
                 {
                     $modelc=M("info");
                     $modeld=M("ans");
                     $modeluser=M("user");
                     if($search[$myflag]['z']>2.9*$search[$myflag]['f'])
                     {
                         $modelc->where("id=$search[$myflag]['id']")->setField("state",1);
                         $search[$myflag]['state']=1;
                         
                         $asker=$modelc->where("id=$search[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("bl",1);
                         $ansuser=$modeld->where("info_id=$search[$myflag]['id'] and zorf=1")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                         
                     }
                     else if($search[$myflag]['f']>2.9*$search[$myflag]['z'])
                     {
                         $modelc->where("id=$search[$myflag]['id']")->setField("state",2);
                         $search[$myflag]['state']=2;
                         
                         $asker=$modelc->where("id=$search[$myflag]['id']")->find();
                         $modeluser->where("username='$asker[author_userid]'")->setInc("zy",1);
                         $ansuser=$modeld->where("info_id=$search[$myflag]['id'] and zorf=0")->select();
                         $i=0;
                         while($ansuser[$i])
                         {
                             $tempuser=$ansuser[$i]['answer_id'];
                             $modeluser->where("username='$tempuser'")->setInc("zx",1);
                             $i++;
                             
                         }
                         
                         
                     }
                     else
                     {
                         $modelc->where("id=$search[$myflag]['id']")->setField("state",3);
                         $search[$myflag]['state']=3;
                         
                         
                         
                         
                         
                     }

                 }

            }
            else
            {
                $day=floor($mytemp/86400);
                $mytemp=$mytemp-$day*86400;
                $hour=floor($mytemp/3600);
                $mytemp=$mytemp-$hour*3600;

                $minute=floor($mytemp/60);
                $second=$mytemp-$minute*60;
                $end_time=NULL;
                if($day)
                {
                    $end_time=$end_time.$day."天";
                }
                if($hour)
                {
                    $end_time=$end_time.$hour."小时";
                }
                if($minute)
                {
                    $end_time=$end_time.$minute."分";
                }
                if($second)
                {
                    $end_time=$end_time.$second."秒";
                }

                $search[$myflag]['end_time']=$end_time;
            }
            $myflag++;
        }







            $this->assign('page',$show);
            $this->assign('thetruth',$search);


		$this->display();
	}
    public function faweibo()
    {
        session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
         if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
        $id=$_REQUEST['truthid'];
        $info=$_REQUEST['text'];
        $url="http://thetruth.sinaapp.com/login.php/Index/answerlist/id/".$id.".html";
        $a=$c->shorten($url);
        $mytext='"'.$info.'"'."     到底是真是假，我在find_the_truth发起了求真相".$a['urls'][0]['url_short'];
        $this->assign('mytext',$mytext);
        $this->assign('info',$info);
        $this->display();
    }
    public function faweiboact()
    {
        session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $text=$_REQUEST['zhuanfa'];
        $ret=$c->update($text);
        if ( isset($ret['error_code']) && $ret['error_code'] > 0 )
        {
			$this->error("发布失败,请重试");
		}
        else
        {
            $this->success("发布成功",U('Index/index'));
		}
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
        $day=$_REQUEST['day'];
        $hour=$_REQUEST['hour'];
        $minute=$_REQUEST['minute'];
        $second=$_REQUEST['second'];
        $actor=$_REQUEST['actor'];
        $lasttime=$day*86400+$hour*3600+$minute*60+$second;
        if($lasttime!=0)
        $mydata['time']=$lasttime;
        if($actor)
        $mydata['actor']=$actor;
        if(!$actor&&$lasttime==0)
            $this->error('时间和人数控制不可同时为0!');
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
				$this->success('提交成功',U('Index/faweibo',array('truthid'=>$result,'text'=>$text)));
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
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$usrname = $user_message['screen_name'];
         if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
		$id = $_REQUEST['id'];
		if(!$id){$this->error('系统查找不到该操作,请重试!');}



	

        import ( '@.ORG.Page' );
		$model=M("ans");
		$model2=M("info");
        $modeluser=M("user");
        $count=$model->where("info_id=$id")->count();
        $Page = new Page($count,10);
        $show = $Page->show();
		$list=$model->where("info_id=$id")->order('newtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $mytag2=0;
        while($list[$mytag2])
        {
            $nametemp=$list[$mytag2]['answer_id'];
            $userm=$modeluser->where("username='$nametemp'")->find();
            $list[$mytag2]['zxdr']=$userm['zx'];
            $mytag2++;
        }
        
        
        
		$info=$model2->where("id=$id")->find();

        $nametemp2=$info['author_userid'];
        $userm2=$modeluser->where("username='$nametemp2'")->find();
        $info['zydr']=$userm2['zy'];
        $info['bldr']=$userm2['bl'];
        
		$this->assign('info',$info);
		$this->assign('anslist',$list);
        $this->assign('page',$show);
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
            $mydata['newtime']=time();

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
                        $modeld=M("ans");
                        $modeluser=M("user");
                        
                        
                        if( $modeljudge['z'] + $modeljudge['f'] >= $modeljudge['actor'] &&  time() > $modeljudge['time'] + $modeljudge['create_time'])
                        {
                            if($modeljudge['z'] > 2.9*$modeljudge['f'])
                            {
                                 $modelc->where("id=$id")->setField("state",1);
                                 
                                
                                 $asker=$modelc->where("id=$id")->find();
                                 $modeluser->where("username='$asker[author_userid]'")->setInc("bl",1);
                                 $ansuser=$modeld->where("info_id=$id and zorf=1")->select();
                                 $i=0;
                                 while($ansuser[$i])
                                 {
                                     $tempuser=$ansuser[$i]['answer_id'];
                                     $modeluser->where("username='$tempuser'")->setInc("zx",1);
                                     $i++;  
                                 }
                                        
                            }
                            else if($modeljudge['f']>2.9*$modeljudge['z'])
                            {
                                 $modelc->where("id=$id")->setField("state",2);
                                
                                 $asker=$modelc->where("id=$id")->find();
                                 $modeluser->where("username='$asker[author_userid]'")->setInc("zy",1);
                                 $ansuser=$modeld->where("info_id=$id and zorf=0")->select();
                                 $i=0;
                                 while($ansuser[$i])
                                 {
                                     $tempuser=$ansuser[$i]['answer_id'];
                                     $modeluser->where("username='$tempuser'")->setInc("zx",1);
                                     $i++;  
                                 }
                                
                                
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
                    $modelb->where("id=$id")->setField("newtime",time());

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


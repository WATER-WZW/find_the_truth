<?php
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
class TruthAction extends Action 
{
    public function index() 
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
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
		$ms  = $c->home_timeline(1,50,0,0,0,1);
        $usrname = $user_message['screen_name'];
        $this->assign('weibolist',$ms['statuses']);
        $this->assign('username',$usrname);
        
        for($i=0;$i<10000;$i++)
                        ;
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
		$usrname = $user_message['screen_name'];
         if(!$usrname)
        {
            $this->error('您还没有授权，现在转移到授权页面','http://thetruth.sinaapp.com/');
        }
        $weiboid=$_REQUEST['id'];
		$info=$c->show_status($weiboid);
        $this->assign('info',$info);
        $this->display();
    }
    public function zhuanfa() 
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
        $weiboid=$_REQUEST['id'];
        $id=$_REQUEST['truthid'];
        $info=$c->show_status($weiboid);
        $url="http://thetruth.sinaapp.com/login.php/Index/answerlist/id/".$id.".html";
        $a=$c->shorten($url);
        $mytext="我在find_the_truth对该微博发起了求真相".$a['urls'][0]['url_short'];
        $this->assign('mytext',$mytext);
        $this->assign('info',$info);
        $this->display();
    }
    public function weibozhuanfa() 
    {
        session_start();
		error_reporting(1);
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $weiboid=$_REQUEST['id'];
        $text=$_REQUEST['zhuanfa'];
        $ret=$c->repost($weiboid,$text);
        if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) 
        {
			$this->error("转发失败,请重试");
		} 
        else 
        {
            $this->success("转发成功",U('Index/index'));
		}
    }
    public function textsubmit() 
    {
        session_start();
		error_reporting(1);
        
        
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
        
        
        
        $weiboid=$_REQUEST['id'];
        $info=$c->show_status($weiboid);
        $day=$_REQUEST['day'];
        $hour=$_REQUEST['hour'];
        $minute=$_REQUEST['minute'];
        $second=$_REQUEST['second'];
        $actor=$_REQUEST['actor'];
        
        $lasttime=$day*86400+$hour*3600+$minute*60+$second;
		if(!$weiboid){$this->error('系统查找不到该操作,请重试!');}
		$model=D("info");
		$mydata['content']=$info['text'];
        if($lasttime!=0)
        $mydata['time']=$lasttime;
        if($actor)
        $mydata['actor']=$actor;
        if($info['bmiddle_pic'])
            $mydata['url']=$info['bmiddle_pic'];
        
        if(!$actor&&$lasttime==0)
            $this->error('时间和人数控制不可同时为0!');
        
		$mydata['author_userid']=$user_message['screen_name'];
        $mydata['create_time']=time();
		$mydata['state']=0;
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
                $this->success('提交成功',U('Truth/zhuanfa',array('id'=>$weiboid,'truthid'=>$result)));
			}
			else{$this->error('提交失败');}
		}
		else
		{
			$this->error('提交失败');
		}
		
        
    }
    public function friend() 
    {
        session_start();
		error_reporting(1);
        
        
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
        $uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);
        
        
        
        
        
        
    }
    
    public function user() 
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
        $keyword=$_REQUEST['name'];
        $namelist=$c->search_at_users($keyword,30);
        $this->assign('namelist',$namelist);
        $this->display();
        
        
    }
    
     public function weibo() 
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
         
        $id=$_REQUEST['id'];
         
         $i=0;
         $j=1;
         $ms=array();
        while($i<20&&$j<10)
        {
            $temp=$c->home_timeline($j,100,0,0,0,1);
            $j++;
            
            if($temp['statuses'])
            {
                for($x=0;$x<50;$x++)
                {
                    if($temp['statuses'][$x]['user']['id']==$id)
                    {
                        $ms[$i]=$temp['statuses'][$x];
                        $i++;
                    }
                }
            }
            else
            {
                break;
            }
        }
        
        $this->assign('weibolist',$ms);
        
		$this->display();
        
        
    }
    
    
    
    
    
    
    
    
}
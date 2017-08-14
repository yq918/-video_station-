<?php
/**
 * @name CommentController
 * @author zxr
 * @desc 评论
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
 
use Base\Tools;     //libary 
use controllers\Video\Bili;
use controllers\Comment\Comment;
use vhost\www\controllers\Traits\DataTraits;  
use vhost\www\controllers\Traits\UserTraits; 
use User\User as LibUser;
use Base\Base;
 
 
class CommentController extends InitController{

    use DataTraits;
    use UserTraits;

	public function init(){
		     parent::init();	 
	} 

   /**
    * [published description]
    * @return [type] [保存评论]
    */
    public function publishedAction()
    {
    	$isPost = $this->getRequest()->isPost();
        $userInfo = LibUser::getCookieUser(); 
        if(!$isPost){
        	Tools::ajaxReturn(false, '非法操作');
        }
        $postData = $this->getRequest()->getPost();
        if(empty($postData) || empty($postData['id']) || empty($postData['type'])){
        	Tools::ajaxReturn(false, '数据为空');
        }
        if($postData['formKey'] != $this->getSessionKey()){
        	Tools::ajaxReturn(false, '非法请求');
        }  
        if(empty($userInfo)){
            Tools::ajaxReturn(false, '请先登录');
        }  
        $saveData = array(
             'video_id' => intval( $postData['id'] ),
             'comment'  => addslashes($postData['content']),
             'user_id'  => $userInfo['id'],
             'video_type' => $postData['type'] == 'bili' ? 1 : 2,
             'post_id'    => isset($postData['post_id']) ? $postData['post_id'] : 0,
             'add_date'   => time()
        	);  
        $Comment = new Comment();
        $data =   $Comment->add($saveData); 
        if($data['id'] > 0){
        	Tools::ajaxReturn(true, '提交成功');
        }else{
        	Tools::ajaxReturn(false, '请交失败');
        } 
     }


     /**
      * [getCommentList 获取评论列表]
      * @return [type] [description]
      */
     public function getCommentListAction()
     {
        $id   = $this->getRequest()->getQuery("id", 0);
        $type = $this->getRequest()->getQuery("type", "");
        if(empty($id) || empty($type)){
             Tools::ajaxReturn(true, 'success',[]);
        } 
        $id   = intval($id);
        $video_type = $type == 'bili' ? 1 : 2;
        $commitList = $this->getCommitList($id,$video_type);
        if(empty( $commitList )){
            Tools::ajaxReturn(true, 'success',[]);
        }
        $useridList = array_column($commitList, 'user_id'); 

        //获取用户信息
        $userInfoList = $this->getUserInfoByUserIdList($useridList);
        foreach ($userInfoList as $key => $value) {
            $user_id = $value['id'];
            $value['nickname'] =  $this->getNickName($value);
            foreach ($commitList as $index => $commeitVal) {
                if($commeitVal['user_id'] == $user_id ){
                    $commeitVal = array_merge($commeitVal,$value);
                }
               $commitList[$index] = $commeitVal;
            } 
        } 
     Tools::ajaxReturn(true, 'success',$commitList);

        // echo "<pre>";
        // print_r($commitList); 
        // exit; 
     }



  


















	
	public function indexAction()
	{    
	   $sing = $this->getRequest()->getQuery("sing", "");
	   $cat_id = Tools::parameterDecryption($sing);  
	   if(empty($cat_id)){
	   	      Base::notFound();
	   } 
        $data = $this->getBiliDetailsVideo($cat_id);  
		$this->getView()->assign("data", $data); 
		$this->assignOptions('index_index');
		$viewPath = VIEWPATH.'/www/funny/funny.phtml'; 
		$this->getView()->display($viewPath); 
	}  
}

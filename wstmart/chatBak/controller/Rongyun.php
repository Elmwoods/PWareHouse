<?php
namespace wstmart\chat\controller;
use think\Controller;
use think\Db;
use RongYunServer\YunXin\Rongcloud;
class Rongyun extends Controller
{
    private $RongCloud;
    private $appKey='n19jmcy5nejo9';
    private $appSecret='ql8rxPItynllG';
    public function _initialize() {
        $this->RongCloud = new Rongcloud($this->appKey, $this->appSecret);
    }
    /*--------------------------用户操作---------------------------*/
    /**
     * 获取 Token 方法 
     * 
     * @param  userId:用户 Id，最大长度 64 字节.是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。（必传）
     * @param  name:用户名称，最大长度 128 字节.用来在 Push 推送时显示用户的名称.用户名称，最大长度 128 字节.用来在 Push 推送时显示用户的名称。（必传）
     * @param  portraitUri:用户头像 URI，最大长度 1024 字节.用来在 Push 推送时显示用户的头像。（必传）
     *
     * @return $json
     **/
    public function getTokens($userId,$name,$portraitUri){
        //判断token是否存在
        // if(!$result['token']){
        //     //不存在调用gettoken方法生成
        //     $info = $this->RongCloud->user()->getToken($userid,$name,$portraitUri);
        // }else{
        //     //存在直接返回
        //     $info=json_encode($result);
        //     //去除json_encode之后多出的
        //     $info=str_replace('\\','',$info);
        // }
        // //获得token
        // $token=json_decode($info,true)['token'];
        // //获得code
        // $code=json_decode($info,true)['code'];
        // //如果info里的token不等于数据里查出来的token 更新数据库
        // if ($result['token']!=$token) {
        //     $model->where('id',$uid)->update(['token'=>$token,'code'=>$code]);
        // }
        //调用getToken方法获得token
        $info = $this->RongCloud->user()->getToken($userId,$name,$portraitUri);
        return $info;
    }
   /**
     * 刷新用户信息方法 
     * 
     * @param  userId:用户 Id，最大长度 64 字节.是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。（必传）
     * @param  name:用户名称，最大长度 128 字节。用来在 Push 推送时，显示用户的名称，刷新用户名称后 5 分钟内生效。（可选，提供即刷新，不提供忽略）
     * @param  portraitUri:用户头像 URI，最大长度 1024 字节。用来在 Push 推送时显示。（可选，提供即刷新，不提供忽略）
     *
     * @return $json
     **/
    public function refreshs($userId,$name,$portraitUri){
        $model=Db::name('gettoken');
        //判断name是否为空 不为空更新数据库
        if (!empty($name)) {
            $model->where('id',$userId)->update(['username'=>$name]);
        }
        //判断portraitUri是否为空 不为空更新数据库
        if (!empty($portraitUri)) {
            $model->where('id',$userId)->update(['icon'=>$portraitUri]);
        }
        //调用refresh方法刷新用户信息
        $info = $this->RongCloud->user()->refresh($userId,$name,$portraitUri);
        return $info;
    }
    /**
     * 检查用户在线状态 方法 
     * 
     * @param  userId:用户 Id，最大长度 64 字节。是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。（必传）
     *
     * @return $json
     **/
    public function checkOnlines($userId){
        //调用checkOnline方法检查用户在线信息
        $info = $this->RongCloud->user()->checkOnline($userId);
        return $info;
    }
    /**
     * 封禁用户方法（每秒钟限 100 次） 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  minute:封禁时长,单位为分钟，最大值为43200分钟。（必传）
     *
     * @return $json
     **/
    public function blocks($userId,$time){
        //调用block方法封禁用户
        $info = $this->RongCloud->user()->block($userId,$time);
        return $info;
    }
     /**
     * 解除用户封禁方法（每秒钟限 100 次） 
     * 
     * @param  userId:用户 Id。（必传）
     *
     * @return $json
     **/
    public function unBlocks($userId){
        //调用unBlock方法解除用户封禁
        $info = $this->RongCloud->user()->unBlock($userId);
        return $info;
    }
     /**
     * 获取被封禁用户方法（每秒钟限 100 次） 
     * 
     *
     * @return $json
     **/
    public function queryBlocks(){
        //调用queryBlock获取被封禁用户
        $info = $this->RongCloud->user()->queryBlock();
        return $info;
    }
    /**
     * 添加用户到黑名单方法（每秒钟限 100 次） 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  blackUserId:被加到黑名单的用户Id。（必传）
     *
     * @return $json
     **/
    public function addBlacklists($userId,$blackUserId){
        //调用addBlacklist方法添加用户到黑名单
        $info = $this->RongCloud->user()->addBlacklist($userId,$blackUserId);
        return $info;
    }
    /**
     * 获取某用户的黑名单列表方法（每秒钟限 100 次） 
     * 
     * @param  userId:用户 Id。（必传）
     *
     * @return $json
     **/
    public function queryBlacklists($userId){
        //调用queryBlacklist方法获取某用户的黑名单列表
        $info = $this->RongCloud->user()->queryBlacklist($userId);
        return $info;
    }
    /**
     * 从黑名单中移除用户方法（每秒钟限 100 次） 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  blackUserId:被移除的用户Id。（必传）
     *
     * @return $json
     **/
    public function removeBlacklists($userId,$blackUserId){
        //调用removeBlacklist方法从黑名单中移除用户
        $info = $this->RongCloud->user()->removeBlacklist($userId,$blackUserId);
        return $info;
    }
    /*--------------------------消息处理---------------------------*/
    /**
     * 发送单聊消息方法（一个用户向另外一个用户发送消息，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人，如：一次发送 1000 人时，示为 1000 条消息。） 
     * 
     * @param  fromUserId:发送人用户 Id。（必传）
     * @param  toUserId:接收用户 Id，可以实现向多人发送消息，每次上限为 1000 人。（必传）
     * @param  voiceMessage:消息。
     * @param  pushContent:定义显示的 Push 内容，如果 objectName 为融云内置消息类型时，则发送后用户一定会收到 Push 信息。如果为自定义消息，则 pushContent 为自定义消息显示的 Push 内容，如果不传则用户不会收到 Push 通知。（可选）
     * @param  pushData:针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息时对应字段名为 pushData。（可选）
     * @param  count:针对 iOS 平台，Push 时用来控制未读消息显示数，只有在 toUserId 为一个用户 Id 的时候有效。（可选）
     * @param  verifyBlacklist:是否过滤发送人黑名单列表，0 表示为不过滤、 1 表示为过滤，默认为 0 不过滤。（可选）
     * @param  isPersisted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行存储，0 表示为不存储、 1 表示为存储，默认为 1 存储消息。（可选）
     * @param  isCounted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行未读消息计数，0 表示为不计数、 1 表示为计数，默认为 1 计数，未读消息数增加 1。（可选）
     * @param  isIncludeSender:发送用户自已是否接收消息，0 表示为不接收，1 表示为接收，默认为 0 不接收。（可选）
     *
     * @return $json
     **/
    public function publishPrivates($fromUserId,$toUserId,$objectName,$content,$pushContent,$pushData,$count,$verifyBlacklist,$isPersisted,$isCounted,$isIncludeSender){
        //调用publishPrivate方法发送单聊消息
        $info = $this->RongCloud->message()->publishPrivate($fromUserId,$toUserId,$objectName,$content,$pushContent,$pushData,$count, $verifyBlacklist,$isPersisted,$isCounted,$isIncludeSender);
        return $info;
    }
   /**
     * 发送单聊模板消息方法（一个用户向多个用户发送不同消息内容，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人。） 
     * 
     * @param  templateMessage:单聊模版消息。
     *
     * @return $json
     **/
    public function publishTemplates(){
        //读取本地模板
        $messagemodel=file_get_contents('./jsonsource/TemplateMessage.json');
        //调用publishTemplates方法发送发送单聊模板
        $info = $this->RongCloud->message()->publishTemplate($messagemodel);
        return $info;
    }
    /**
     * 发送系统消息方法（一个用户向一个或多个用户发送系统消息，单条消息最大 128k，会话类型为 SYSTEM。每秒钟最多发送 100 条消息，每次最多同时向 100 人发送，如：一次发送 100 人时，示为 100 条消息。） 
     * 
     * @param  fromUserId:发送人用户 Id。（必传）
     * @param  toUserId:接收用户 Id，提供多个本参数可以实现向多人发送消息，上限为 1000 人。（必传）
     * @param  txtMessage:发送消息内容（必传）
     * @param  pushContent:如果为自定义消息，定义显示的 Push 内容，内容中定义标识通过 values 中设置的标识位内容进行替换.如消息类型为自定义不需要 Push 通知，则对应数组传空值即可。（可选）
     * @param  pushData:针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息时对应字段名为 pushData。如不需要 Push 功能对应数组传空值即可。（可选）
     * @param  isPersisted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行存储，0 表示为不存储、 1 表示为存储，默认为 1 存储消息。（可选）
     * @param  isCounted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行未读消息计数，0 表示为不计数、 1 表示为计数，默认为 1 计数，未读消息数增加 1。（可选）
     *
     * @return $json
     **/
    public function PublishSystems($fromUserId,$toUserId,$objectName,$content,$pushContent='',$pushData='',$isPersisted = 1,$isCounted = 1){
        //调用PublishSystem方法发送系统消息
        $info = $this->RongCloud->message()->PublishSystem($fromUserId,$toUserId,$objectName,$content,$pushContent,$pushData,$isPersisted,$isCounted);
        return $info;
    }
    /**
     * 发送系统模板消息方法（一个用户向一个或多个用户发送系统消息，单条消息最大 128k，会话类型为 SYSTEM.每秒钟最多发送 100 条消息，每次最多同时向 100 人发送，如：一次发送 100 人时，示为 100 条消息。） 
     * 
     * @param  templateMessage:系统模版消息。
     *
     * @return $json
     **/
    public function publishSystemTemplates(){
        //读取本地系统模板
        $systemmodel=file_get_contents('./jsonsource/TemplateMessage.json');
        //调用publishSystemTemplate方法发送系统模板
        $info = $this->RongCloud->message()->publishSystemTemplate($systemmodel);
        return $info;
    }
    /**
     * 发送群组消息方法（以一个用户身份向群组发送消息，单条消息最大 128k.每秒钟最多发送 20 条消息，每次最多向 3 个群组发送，如：一次向 3 个群组发送消息，示为 3 条消息。） 
     * 
     * @param  fromUserId:发送人用户 Id 。（必传）
     * @param  toGroupId:接收群Id，提供多个本参数可以实现向多群发送消息，最多不超过 3 个群组。（必传）
     * @param  txtMessage:发送消息内容（必传）
     * @param  pushContent:定义显示的 Push 内容，如果 objectName 为融云内置消息类型时，则发送后用户一定会收到 Push 信息. 如果为自定义消息，则 pushContent 为自定义消息显示的 Push 内容，如果不传则用户不会收到 Push 通知。（可选）
     * @param  pushData:针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息时对应字段名为 pushData。（可选）
     * @param  isPersisted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行存储，0 表示为不存储、 1 表示为存储，默认为 1 存储消息。（可选）
     * @param  isCounted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行未读消息计数，0 表示为不计数、 1 表示为计数，默认为 1 计数，未读消息数增加 1。（可选）
     * @param  isIncludeSender:发送用户自已是否接收消息，0 表示为不接收，1 表示为接收，默认为 0 不接收。（可选）
     *
     * @return $json
     **/
    public function publishGroups($fromUserId,$toGroupId,$objectName,$content,$pushContent = '',$pushData = '',$isPersisted = 1,$isCounted = 1, $isIncludeSender = 1){
        //调用publishGroup方法发送群组消息
        $info = $this->RongCloud->message()->publishGroup($fromUserId,$toGroupId,$objectName,$content, $pushContent,$pushData, $isPersisted,$isCounted,$isIncludeSender);
        return $info;
    }
    /**
     * 发送讨论组消息方法（以一个用户身份向讨论组发送消息，单条消息最大 128k，每秒钟最多发送 20 条消息.） 
     * 
     * @param  fromUserId:发送人用户 Id。（必传）
     * @param  toDiscussionId:接收讨论组 Id。（必传）
     * @param  txtMessage:发送消息内容（必传）
     * @param  pushContent:定义显示的 Push 内容，如果 objectName 为融云内置消息类型时，则发送后用户一定会收到 Push 信息. 如果为自定义消息，则 pushContent 为自定义消息显示的 Push 内容，如果不传则用户不会收到 Push 通知。（可选）
     * @param  pushData:针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息时对应字段名为 pushData.（可选）
     * @param  isPersisted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行存储，0 表示为不存储、 1 表示为存储，默认为 1 存储消息.（可选）
     * @param  isCounted:当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行未读消息计数，0 表示为不计数、 1 表示为计数，默认为 1 计数，未读消息数增加 1。（可选）
     * @param  isIncludeSender:发送用户自已是否接收消息，0 表示为不接收，1 表示为接收，默认为 0 不接收。（可选）
     *
     * @return $json
     **/
    public function publishDiscussions($fromUserId,$toDiscussionId,$objectName,$content,$pushContent,$pushData,$isPersisted,$isCounted,$isIncludeSender){
        //调用publishDiscussion方法发送讨论组消息
        $info = $this->RongCloud->message()->publishDiscussion($fromUserId,$toDiscussionId,$objectName,$content,$pushContent,$pushData,$isPersisted,$isCounted,$isIncludeSender);
        return $info;
    }
    /**
     * 发送聊天室消息方法（一个用户向聊天室发送消息，单条消息最大 128k。每秒钟限 100 次。） 
     * 
     * @param  fromUserId:发送人用户 Id。（必传）
     * @param  toChatroomId:接收聊天室Id，提供多个本参数可以实现向多个聊天室发送消息。（必传）
     * @param  txtMessage:发送消息内容（必传）
     *
     * @return $json
     **/
    public function publishChatrooms($fromUserId,$toChatroomId,$objectName,$content){
        //调用publishChatroom方法发送聊天室消息
        $info = $this->RongCloud->message()->publishChatroom($fromUserId,$toChatroomId,$objectName,$content);
        return $info;
    }
     /**
     * 发送广播消息方法（发送消息给一个应用下的所有注册用户，如用户未在线会对满足条件（绑定手机终端）的用户发送 Push 信息，单条消息最大 128k，会话类型为 SYSTEM。每小时只能发送 1 次，每天最多发送 3 次。） 
     * 
     * @param  fromUserId:发送人用户 Id。（必传）
     * @param  txtMessage:文本消息。
     * @param  pushContent:定义显示的 Push 内容，如果 objectName 为融云内置消息类型时，则发送后用户一定会收到 Push 信息. 如果为自定义消息，则 pushContent 为自定义消息显示的 Push 内容，如果不传则用户不会收到 Push 通知.（可选）
     * @param  pushData:针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息时对应字段名为 pushData。（可选）
     * @param  os:针对操作系统发送 Push，值为 iOS 表示对 iOS 手机用户发送 Push ,为 Android 时表示对 Android 手机用户发送 Push ，如对所有用户发送 Push 信息，则不需要传 os 参数。（可选）
     *
     * @return $json
     **/
    public function broadcasts($fromUserId,$objectName,$content,$pushContent,$pushData,$os){
        //调用broadcast方法发送广播消息
        $info = $this->RongCloud->message()->broadcast($fromUserId,$objectName,$content,$pushContent,$pushData,$os);
        return $info;
    }
    /**
     * 消息历史记录下载地址获取 方法消息历史记录下载地址获取方法。获取 APP 内指定某天某小时内的所有会话消息记录的下载地址。（目前支持二人会话、讨论组、群组、聊天室、客服、系统通知消息历史记录下载） 
     * 
     * @param  date:指定北京时间某天某小时，格式为2014010101,表示：2014年1月1日凌晨1点。（必传）
     *
     * @return $json
     **/
    public function getHistorys($date){
        //调用getHistory指定某天某小时内的所有会话消息记录的下载地址。
        $info = $this->RongCloud->message()->getHistory($date);
        return $info;
    }
    /**
     * 消息历史记录删除方法（删除 APP 内指定某天某小时内的所有会话消息记录。调用该接口返回成功后，date参数指定的某小时的消息记录文件将在随后的5-10分钟内被永久删除。） 
     * 
     * @param  date:指定北京时间某天某小时，格式为2014010101,表示：2014年1月1日凌晨1点。（必传）
     *
     * @return $json
     **/
    public function deleteMessages($date){
        //调用deleteMessage消息记录
        $info = $this->RongCloud->message()->deleteMessage($date);
        return $info;
    }
    /*--------------------------敏感词---------------------------*/
    /**
     * 添加敏感词方法（设置敏感词后，App 中用户不会收到含有敏感词的消息内容，默认最多设置 50 个敏感词。） 
     * 
     * @param  word:敏感词，最长不超过 32 个字符。（必传）
     *
     * @return $json
     **/
    public function adds($word,$replaceWord){
        //调用add添加敏感词
        $info = $this->RongCloud->sensitiveword()->add($word,$replaceWord);
        return $info;
    }
    /**
     * 查询敏感词列表方法 
     * 
     *
     * @return $json
     **/
    public function getLists(){
        //调用getList查询敏感词列表
        $info = $this->RongCloud->sensitiveword()->getList();
        return $info;
    }
     /**
     * 移除敏感词方法（从敏感词列表中，移除某一敏感词。） 
     * 
     * @param  word:敏感词，最长不超过 32 个字符。（必传）
     *
     * @return $json
     **/
    public function deletes($word){
        //调用delete删除敏感词
        $info = $this->RongCloud->sensitiveword()->delete($word);
        return $info;
    }
    /*--------------------------群组操作---------------------------*/
    /**
     * 创建群组方法（创建群组，并将用户加入该群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人，App 内的群组数量没有限制.注：其实本方法是加入群组方法 /group/join 的别名。） 
     * 
     * @param  userId:要加入群的用户 Id。（必传）
     * @param  groupId:创建群组 Id。（必传）
     * @param  groupName:群组 Id 对应的名称。（必传）
     *
     * @return $json
     **/
    public function creates($userId,$groupId,$groupName){
        //调用create创建群组
        $info = $this->RongCloud->group()->create($userId,$groupId,$groupName);
        return $info;
    }
    /**
     * 同步用户所属群组方法（当第一次连接融云服务器时，需要向融云服务器提交 userId 对应的用户当前所加入的所有群组，此接口主要为防止应用中用户群信息同融云已知的用户所属群信息不同步。） 
     * 
     * @param  userId:被同步群信息的用户 Id。（必传）
     * @param  groupInfo:该用户的群信息，如群 Id 已经存在，则不会刷新对应群组名称，如果想刷新群组名称请调用刷新群组信息方法。
     *
     * @return $json
     **/
    public function syncs($userId,$groupInfo){
        //调用sync同步用户所属群组
        $info = $this->RongCloud->group()->sync($userId, $groupInfo);
        return $info;
    }
    /**
     * 刷新群组信息方法 
     * 
     * @param  groupId:群组 Id。（必传）
     * @param  groupName:群名称。（必传）
     *
     * @return $json
     **/
    public function refreshsGroup($groupId,$groupName){
        //调用refresh刷新群组信息
        $info = $this->RongCloud->group()->refresh($groupId,$groupName);
        return $info;
    }
    /**
     * 将用户加入指定群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人。 
     * 
     * @param  userId:要加入群的用户 Id，可提交多个，最多不超过 1000 个。（必传）
     * @param  groupId:要加入的群 Id。（必传）
     * @param  groupName:要加入的群 Id 对应的名称。（必传）
     *
     * @return $json
     **/
    public function joins($userId,$groupId,$groupName){
        //调用join将用户加入指定群组
        $info = $this->RongCloud->group()->join($userId,$groupId,$groupName);
        return $info;
    }
    /**
     * 查询群成员方法 
     * 
     * @param  groupId:群组Id。（必传）
     *
     * @return $json
     **/
    public function queryUsers($groupId){
        //调用queryUser查询群成员
        $info = $this->RongCloud->group()->queryUser($groupId);
        return $info;
    }
    /**
     * 退出群组方法（将用户从群中移除，不再接收该群组的消息.） 
     * 
     * @param  userId:要退出群的用户 Id.（必传）
     * @param  groupId:要退出的群 Id.（必传）
     *
     * @return $json
     **/
    public function quits($userId,$groupId){
        //调用quit退出群组
        $info = $this->RongCloud->group()->quit($userId,$groupId);
        return $info;
    }
    /**
     * 添加禁言群成员方法（在 App 中如果不想让某一用户在群中发言时，可将此用户在群组中禁言，被禁言用户可以接收查看群组中用户聊天信息，但不能发送消息。） 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  groupId:群组 Id。（必传）
     * @param  minute:禁言时长，以分钟为单位，最大值为43200分钟。（必传）
     *
     * @return $json
     **/
    public function addGagUsers($userId,$groupId,$minute){
        //调用addGagUser添加禁言群成员
        $info = $this->RongCloud->group()->addGagUser($userId,$groupId,$minute);
        return $info;
    }
    /**
     * 查询被禁言群成员方法 
     * 
     * @param  groupId:群组Id。（必传）
     *
     * @return $json
     **/
    public function lisGagUsers($groupId){
        //调用lisGagUser查询被禁言群成员
        $info = $this->RongCloud->group()->lisGagUser($groupId);
        return $info;
    }
    /**
     * 移除禁言群成员方法 
     * 
     * @param  userId:用户Id。支持同时移除多个群成员（必传）
     * @param  groupId:群组Id。（必传）
     *
     * @return $json
     **/
    public function rollBackGagUsers($userId,$groupId){
        //调用rollBackGagUser移除禁言群成员
        $info = $this->RongCloud->group()->rollBackGagUser($userId,$groupId);
        return $info;
    }
    /**
     * 解散群组方法。（将该群解散，所有用户都无法再接收该群的消息。） 
     * 
     * @param  userId:操作解散群的用户 Id。（必传）
     * @param  groupId:要解散的群 Id。（必传）
     *
     * @return $json
     **/
    public function dismisss($userId,$groupId){
        //调用dismiss解散群组
        $info = $this->RongCloud->group()->dismiss($userId,$groupId);
        return $info;
    }
    /*--------------------------聊天室操作---------------------------*/
     /**
     * 创建聊天室方法 
     * 
     * @param  chatRoomInfo:id:要创建的聊天室的id；name:要创建的聊天室的name。（必传）
     *
     * @return $json
     **/
     public function createChat($chatRoomInfo){
        //示例$chatRoomInfo['chatroomId1'] = 'chatroomInfo1';
        //调用create创建聊天室
        $info = $this->RongCloud->chatroom()->create($chatRoomInfo);
        return $info;
     }
      /**
     * 加入聊天室方法 
     * 
     * @param  userId:要加入聊天室的用户 Id，可提交多个，最多不超过 50 个。（必传）
     * @param  chatroomId:要加入的聊天室 Id。（必传）
     *
     * @return $json
     **/
     public function joinChat($userId,$chatroomId){
        //调用join加入聊天室
        $info = $this->RongCloud->chatroom()->join($userId,$chatroomId);
        return $info;
     }
     /**
     * 查询聊天室信息方法 
     * 
     * @param  chatroomId:要查询的聊天室id（必传）
     *
     * @return $json
     **/
     public function querys($chatroomId){
        //调用query查询聊天室信息
        $info = $this->RongCloud->chatroom()->query($chatroomId);
        return $info;
     }
     /**
     * 查询聊天室内用户方法 
     * 
     * @param  chatroomId:要查询的聊天室 ID。（必传）
     * @param  count:要获取的聊天室成员数，上限为 500 ，超过 500 时最多返回 500 个成员。（必传）
     * @param  order:加入聊天室的先后顺序， 1 为加入时间正序， 2 为加入时间倒序。（必传）
     *
     * @return $json
     **/
     public function chatQueryUser($chatroomId,$count,$order){
        //调用queryUser查询聊天室内用户
        $info = $this->RongCloud->chatroom()->queryUser($chatroomId,$count,$order);
        return $info;
     }
     /**
     * 聊天室消息停止分发方法（可实现控制对聊天室中消息是否进行分发，停止分发后聊天室中用户发送的消息，融云服务端不会再将消息发送给聊天室中其他用户。） 
     * 
     * @param  chatroomId:聊天室 Id。（必传）
     *
     * @return $json
     **/
     public function stopDistributionMessages($chatroomId){
        //调用stopDistributionMessage聊天室消息停止分发
        $info = $this->RongCloud->chatroom()->stopDistributionMessage($chatroomId);
        return $info;
     }
     /**
     * 聊天室消息恢复分发方法 
     * 
     * @param  chatroomId:聊天室 Id。（必传）
     *
     * @return $json
     **/
     public function resumeDistributionMessages($chatroomId){
        //调用resumeDistributionMessage聊天室消息恢复分发
        $info = $this->RongCloud->chatroom()->resumeDistributionMessage($chatroomId);
        return $info;
     }
     /**
     * 添加禁言聊天室成员方法（在 App 中如果不想让某一用户在聊天室中发言时，可将此用户在聊天室中禁言，被禁言用户可以接收查看聊天室中用户聊天信息，但不能发送消息.） 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  chatroomId:聊天室 Id。（必传）
     * @param  minute:禁言时长，以分钟为单位，最大值为43200分钟。（必传）
     *
     * @return $json
     **/
     public function addGagChatUser($userId,$chatroomId,$minute){
        //调用addGagUser添加禁言聊天室成员
        $info = $this->RongCloud->chatroom()->addGagUser($userId,$chatroomId,$minute);
        return $info;
     }
     /**
     * 查询被禁言聊天室成员方法 
     * 
     * @param  chatroomId:聊天室 Id。（必传）
     *
     * @return $json
     **/
     public function ListGagUsers($chatroomId){
        //调用ListGagUser查询被禁言聊天室成员
        $info = $this->RongCloud->chatroom()->ListGagUser($chatroomId);
        return $info;
     }
     /**
     * 移除禁言聊天室成员方法 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  chatroomId:聊天室Id。（必传）
     *
     * @return $json
     **/
     public function rollbackGagChatUser($userId,$chatroomId){
        //调用rollbackGagUser移除禁言聊天室成员
        $info = $this->RongCloud->chatroom()->rollbackGagUser($userId,$chatroomId);
        return $info;
     }
     /**
     * 添加封禁聊天室成员方法 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  chatroomId:聊天室 Id。（必传）
     * @param  minute:封禁时长，以分钟为单位，最大值为43200分钟。（必传）
     *
     * @return $json
     **/
     public function addBlockUsers($userId,$chatroomId,$minute){
        //调用addBlockUser添加封禁聊天室成员
        $info = $this->RongCloud->chatroom()->addBlockUser($userId,$chatroomId,$minute);
        return $info;
     }
     /**
     * 查询被封禁聊天室成员方法 
     * 
     * @param  chatroomId:聊天室 Id。（必传）
     *
     * @return $json
     **/
     public function getListBlockUsers($chatroomId){
        //调用getListBlockUser添加封禁聊天室成员
        $info = $this->RongCloud->chatroom()->getListBlockUser($chatroomId);
        return $info;
     }
      /**
     * 移除封禁聊天室成员方法 
     * 
     * @param  userId:用户 Id。（必传）
     * @param  chatroomId:聊天室 Id。（必传）
     *
     * @return $json
     **/
    public function rollbackBlockUsers($userId,$chatroomId){
        //调用rollbackBlockUser移除封禁聊天室成员
        $info = $this->RongCloud->chatroom()->rollbackBlockUser($userId,$chatroomId);
        return $info;
    }
    /**
     * 添加聊天室消息优先级方法 
     * 
     * @param  objectName:低优先级的消息类型，每次最多提交 5 个，设置的消息类型最多不超过 20 个。（必传）
     *
     * @return $json
     **/
    public function addPrioritys($objectName){
        //调用addPriority添加聊天室消息优先级
        $info = $this->RongCloud->chatroom()->addPriority($objectName);
        return $info;
    }
    /**
     * 销毁聊天室方法 
     * 
     * @param  chatroomId:要销毁的聊天室 Id。（必传）
     *
     * @return $json
     **/
    public function destroys($chatroomId){
        //调用addPriority销毁聊天室
        $info = $this->RongCloud->chatroom()->destroy($chatroomId);
        return $info;
    }
    /**
     * 添加聊天室白名单成员方法 
     * 
     * @param  chatroomId:聊天室中用户 Id，可提交多个，聊天室中白名单用户最多不超过 5 个。（必传）
     * @param  userId:聊天室 Id。（必传）
     *
     * @return $json
     **/
    public function addWhiteListUsers($chatroomId,$userId){
        //调用addWhiteListUser添加聊天室白名单
        $info = $this->RongCloud->chatroom()->addWhiteListUser($chatroomId,$userId);
        return $info;
    }
    /**
     * 查询聊天室白名单成员方法 
     * 
     * @param  chatroomId:要查询的聊天室 ID。（必传）
     * @return $json
     **/
    public function queryWhiteListUsers($chatroomId){
        //调用queryWhiteListUser查询聊天室白名单成员
        $info = $this->RongCloud->chatroom()->queryWhiteListUser($chatroomId);
        return $info;
    }
    /**
     * 移除聊天室白名单成员方法
     *  
     * @param  chatroomId:聊天室Id。（必传）
     * @param  userId:聊天室白名单中用户 Id，可提交多个，最多不超过 5 个。（必传）
     * @return $json
     **/
    public function removeWhiteListUsers($chatroomId,$userId){
        //调用removeWhiteListUser移除聊天室白名单成员
        $info = $this->RongCloud->chatroom()->removeWhiteListUser($chatroomId,$userId);
        return $info;
    }
    /*--------------------------消息推送---------------------------*/
     /**
     * 添加 Push 标签方法 
     * 
     * @param  userTag:用户标签。
     *
     * @return $json
     **/
     public function setUserPushTags(){
        //获得本地push标签
        $jsonPath=file_get_contents('./jsonsource/UserTag.json');
        //调用setUserPushTag添加 Push 标签
        $info = $this->RongCloud->push()->setUserPushTag($jsonPath);
        return $info;
     }
     /**
     * 广播消息方法（fromuserid 和 message为null即为不落地的push） 
     * 
     * @param  pushMessage:json数据
     *
     * @return $json
     **/
     public function broadcastPushs(){
        //获得本地广播消息
        $jsonPath=file_get_contents('./jsonsource/PushMessage.json');
        //调用broadcastPush广播消息
        $info = $this->RongCloud->push()->broadcastPush($jsonPath);
        return $info;
     }
     /*--------------------------验证码---------------------------*/
     /*--------------------------没有开通服务,代码不能运行,无法测试---------------------------*/
      /**
     * 获取图片验证码方法 
     * 
     * @param  appKey:应用Id
     *
     * @return $json
     **/
      public function getImageCodes(){
        //调用getImageCode获取图片验证码
        $info = $this->RongCloud->SMS()->getImageCode($this->appKey);
        return $info;
      }
      /**
     * 发送短信验证码方法。 
     * 
     * @param  mobile:接收短信验证码的目标手机号，每分钟同一手机号只能发送一次短信验证码，同一手机号 1 小时内最多发送 3 次。（必传）
     * @param  templateId:短信模板 Id，在开发者后台->短信服务->服务设置->短信模版中获取。（必传）
     * @param  region:手机号码所属国家区号，目前只支持中图区号 86）
     * @param  verifyId:图片验证标识 Id ，开启图片验证功能后此参数必传，否则可以不传。在获取图片验证码方法返回值中获取。
     * @param  verifyCode:图片验证码，开启图片验证功能后此参数必传，否则可以不传。
     *
     * @return $json
     **/
      public function sendCodes($mobile,$templateId,$region,$verifyId,$verifyCode){
        //调用sendCode发送短信验证码
        $info = $this->RongCloud->SMS()->sendCode($mobile,$templateId,$region,$verifyId,$verifyCode);
        return $info;
      }
      /**
     * 验证码验证方法 
     * 
     * @param  sessionId:短信验证码唯一标识，在发送短信验证码方法，返回值中获取。（必传）
     * @param  code:短信验证码内容。（必传）
     *
     * @return $json
     **/
     public function verifyCodes($sessionId,$code){
        //调用verifyCode进行验证码验证
        $info = $this->RongCloud->SMS()->verifyCode($sessionId,$code);
        return $info;
     }
}
?>
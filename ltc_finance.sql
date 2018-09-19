# Host: localhost  (Version: 5.5.53)
# Date: 2018-08-23 11:39:15
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "think_capital_log"
#

DROP TABLE IF EXISTS `think_capital_log`;
CREATE TABLE `think_capital_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `capital` varchar(20) DEFAULT NULL COMMENT '变动资金',
  `way` tinyint(3) NOT NULL DEFAULT '1' COMMENT '资金变动 方式：1=》系统增加；2=》系统减少；3=》锁仓收益；4=》分享收益；5=》交易支出；6=》交易收入；7=》交易手续费；8=》锁仓减少；9=》开仓增加',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='资金记录';

#
# Data for table "think_capital_log"
#

/*!40000 ALTER TABLE `think_capital_log` DISABLE KEYS */;
INSERT INTO `think_capital_log` VALUES (1,3,'25',1,1530783348,1530783348),(2,4,'30',1,1530783360,1530783360),(3,6,'45',1,1530783371,1530783371),(4,8,'21',1,1530783383,1530783383),(5,9,'32',1,1530783394,1530783394),(6,3,'10',8,1530785315,1530785315),(7,3,'3',10,1530785434,1530785434),(8,3,'9',12,1530785562,1530785562),(11,3,'5',6,1530788912,1530788912),(12,6,'7',5,1530789118,1530789118),(13,6,'0.21',7,1530789118,1530789118),(14,3,'7',6,1530789192,1530789192),(15,4,'9',8,1530789498,1530789498),(16,3,'0.01',13,1530843172,1530843172),(17,6,'2',8,1530844620,1530844620),(18,3,'5',8,1530944808,1530944808),(19,3,'10',9,1530963742,1530963742),(20,3,'0.01',13,1531129005,1531129005),(21,3,'0.075',3,1531129253,1531129253),(22,1,'0.01125',4,1531129253,1531129253),(23,6,'0.02',3,1531129253,1531129253),(24,1,'0.001',4,1531129253,1531129253),(25,3,'0.002',4,1531129253,1531129253),(26,4,'0.003',4,1531129253,1531129253),(27,3,'0.075',3,1531137454,1531137454),(28,1,'0.01125',4,1531137454,1531137454),(29,6,'0.02',3,1531137454,1531137454),(30,1,'0.001',4,1531137454,1531137454),(31,3,'0.002',4,1531137454,1531137454),(32,4,'0.003',4,1531137454,1531137454);
/*!40000 ALTER TABLE `think_capital_log` ENABLE KEYS */;

#
# Structure for table "think_extract"
#

DROP TABLE IF EXISTS `think_extract`;
CREATE TABLE `think_extract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `plat` varchar(255) DEFAULT NULL COMMENT '提币平台',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '提币数量',
  `address` text NOT NULL COMMENT '提币地址',
  `payment` varchar(255) DEFAULT NULL COMMENT 'Payment ID',
  `service_price` varchar(15) DEFAULT NULL COMMENT '手续费',
  `true_num` varchar(15) DEFAULT NULL COMMENT '实际到帐金额',
  `state` tinyint(3) DEFAULT '0' COMMENT '提币状态；0=》审核中；1=》已通过；2=》已驳回',
  `talk` text COMMENT '反馈',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='提币申请';

#
# Data for table "think_extract"
#

/*!40000 ALTER TABLE `think_extract` DISABLE KEYS */;
INSERT INTO `think_extract` VALUES (1,3,'比特币儿(www.abc.com)',3,'65F2A5D4G165F2A5D4G165F2A5D4G165F2A5D4G165F2A5D4G1','D52G7T2A1GHJKI5','0.15','2.85',0,NULL,1530785434,1530785434);
/*!40000 ALTER TABLE `think_extract` ENABLE KEYS */;

#
# Structure for table "think_feed"
#

DROP TABLE IF EXISTS `think_feed`;
CREATE TABLE `think_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `content` text NOT NULL COMMENT '反馈内容',
  `img` text COMMENT '反馈图片',
  `reply` text COMMENT '回复信息',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '处理状态：0=》未处理；1=》已处理',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='反馈';

#
# Data for table "think_feed"
#

/*!40000 ALTER TABLE `think_feed` DISABLE KEYS */;
INSERT INTO `think_feed` VALUES (1,3,'我确认付款了，但对方没有确认收款，怎么搞？','\\uploads/20180706\\57c6cb72f5ec4f02a9b02fe667101d11.jpg',NULL,0,1530841736,1530841736);
/*!40000 ALTER TABLE `think_feed` ENABLE KEYS */;

#
# Structure for table "think_friend"
#

DROP TABLE IF EXISTS `think_friend`;
CREATE TABLE `think_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `p_id` int(11) DEFAULT NULL COMMENT '父级用户ID',
  `grade` tinyint(3) NOT NULL DEFAULT '1' COMMENT '关系等级；1=》上一级；2=》上二级；3=》上三级；4=》上四级；5=》上五级；6=》上六级；7=》上七级；8=》上八级；9=》上九级',
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`p_id`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='分销关系';

#
# Data for table "think_friend"
#

/*!40000 ALTER TABLE `think_friend` DISABLE KEYS */;
INSERT INTO `think_friend` VALUES (1,3,1,1,1530781916),(2,4,3,1,1530782371),(3,4,1,2,1530782371),(4,5,3,1,1530782436),(5,5,1,2,1530782436),(6,6,4,1,1530782522),(7,6,3,2,1530782522),(8,6,1,3,1530782522),(9,7,4,1,1530782564),(10,7,3,2,1530782564),(11,7,1,3,1530782564),(12,8,5,1,1530782609),(13,8,3,2,1530782609),(14,8,1,3,1530782609),(15,9,6,1,1530783106),(16,9,4,2,1530783106),(17,9,3,3,1530783106),(18,9,1,4,1530783106);
/*!40000 ALTER TABLE `think_friend` ENABLE KEYS */;

#
# Structure for table "think_lock"
#

DROP TABLE IF EXISTS `think_lock`;
CREATE TABLE `think_lock` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '锁仓数量',
  `lock_time` int(11) NOT NULL DEFAULT '0' COMMENT '锁仓时间',
  `lock_ratio` varchar(10) NOT NULL DEFAULT '' COMMENT '锁仓利率',
  `state` tinyint(1) DEFAULT '0' COMMENT '锁仓状态；0=》锁仓中；1=》已完成;2=》中断锁仓',
  `is_break` int(10) DEFAULT NULL COMMENT '是否为系统中断锁仓：不是为空，是为时间戳',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='锁仓明细';

#
# Data for table "think_lock"
#

/*!40000 ALTER TABLE `think_lock` DISABLE KEYS */;
INSERT INTO `think_lock` VALUES (1,3,10,180,'2.5',2,1530963741,1530785315,1530785315),(2,4,9,180,'2.5',1,NULL,1530789498,1530789498),(3,6,2,1,'1',0,NULL,1530844620,1530844620),(4,3,5,30,'1.5',0,NULL,1530944808,1530944808);
/*!40000 ALTER TABLE `think_lock` ENABLE KEYS */;

#
# Structure for table "think_log_verify"
#

DROP TABLE IF EXISTS `think_log_verify`;
CREATE TABLE `think_log_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '手机号',
  `verify` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '验证码',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=》注册发送；1=》忘记密码发送；2=》提现发送',
  `request_type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '0=》android；1=》IOS；2=》Web',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=》未使用；1=》已使用',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'ip',
  `c_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `e_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Data for table "think_log_verify"
#

/*!40000 ALTER TABLE `think_log_verify` DISABLE KEYS */;
INSERT INTO `think_log_verify` VALUES (1,18297905431,376416,3,2,1,'127.0.0.1','2018-07-05 18:10:10','2018-07-05 18:10:10'),(2,18297905431,414428,3,2,1,'127.0.0.1','2018-07-05 18:10:19','2018-07-05 18:10:34'),(3,18297905431,638577,4,2,1,'127.0.0.1','2018-07-05 18:14:51','2018-07-05 18:21:04'),(4,18297905431,466833,5,2,1,'127.0.0.1','2018-07-05 18:20:21','2018-07-05 18:20:37'),(5,13564078415,744760,5,2,1,'127.0.0.1','2018-07-05 18:48:11','2018-07-05 18:48:21'),(6,18297905431,709933,4,2,1,'127.0.0.1','2018-07-05 19:09:36','2018-07-05 19:09:46'),(7,17333007330,773214,5,2,1,'127.0.0.1','2018-07-05 19:11:34','2018-07-05 19:11:48'),(8,18297905431,834490,1,2,1,'127.0.0.1','2018-07-09 15:56:26','2018-07-09 15:59:59'),(9,18297905431,314205,1,2,1,'127.0.0.1','2018-07-09 16:00:17','2018-07-09 16:00:27'),(10,18297905431,766979,1,2,0,'127.0.0.1','2018-07-20 14:57:49',NULL);
/*!40000 ALTER TABLE `think_log_verify` ENABLE KEYS */;

#
# Structure for table "think_manager"
#

DROP TABLE IF EXISTS `think_manager`;
CREATE TABLE `think_manager` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '//编号',
  `account` varchar(20) NOT NULL COMMENT '//登录帐户名',
  `power` varchar(20) NOT NULL COMMENT '//角色权限名称',
  `name` varchar(20) NOT NULL COMMENT '//用户真实姓名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '//密码',
  `login_count` tinyint(4) NOT NULL COMMENT '//登录次数统计',
  `last_ip` varchar(15) NOT NULL COMMENT '//最后登录ip',
  `state` smallint(2) NOT NULL DEFAULT '1' COMMENT '//用户状态',
  `last_time` int(10) DEFAULT NULL COMMENT '//最后登录时间',
  `u_time` int(10) DEFAULT NULL COMMENT '//修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员';

#
# Data for table "think_manager"
#

/*!40000 ALTER TABLE `think_manager` DISABLE KEYS */;
INSERT INTO `think_manager` VALUES (1,'zhangsanfeng','超级管理员','张三丰','96e79218965eb72c92a549dd5a330112',50,'192.168.127.23',1,NULL,NULL),(2,'yingzheng','普通管理员','赢政','96e79218965eb72c92a549dd5a330112',30,'192.168.125.23',0,NULL,NULL),(3,'lishiming','订单管理员','李世民','96e79218965eb72c92a549dd5a330112',13,'127.0.0.1',1,1507536011,NULL),(7,'zhuyuanzhang','超级管理员','朱元璋','96e79218965eb72c92a549dd5a330112',0,'',1,NULL,NULL),(8,'chengjisihan','评论管理员','成吉思汗','96e79218965eb72c92a549dd5a330112',0,'',0,NULL,NULL),(18,'zhouwenwang','超级管理员','周文王','111111',0,'',1,NULL,NULL),(19,'admin','超级管理员','admin','96e79218965eb72c92a549dd5a330112',127,'127.0.0.1',1,1531183607,NULL);
/*!40000 ALTER TABLE `think_manager` ENABLE KEYS */;

#
# Structure for table "think_message"
#

DROP TABLE IF EXISTS `think_message`;
CREATE TABLE `think_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '类型：1=》注册协议；2=》LTC简介；3=》提币平台；4=》充币地址；5=》安卓地址；6=》IOS地址；7=》提币申请控制开关',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态；1=》启用；0=》关闭',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='数据信息';

#
# Data for table "think_message"
#

/*!40000 ALTER TABLE `think_message` DISABLE KEYS */;
INSERT INTO `think_message` VALUES (4,NULL,'LXNafhQYRiuXqVhFsru57Q8B8mx6sxDQJB',4,1,1529918762,1529918762),(5,NULL,'比特币儿(www.abc.com)',3,1,1529909435,1529909435),(7,NULL,'火币网（www.cde.com）',3,1,1529921305,1529921305),(8,'用户注册协议','&nbsp; &nbsp; 近年来随着比特币的兴起与发展，其底层技术“区块链”一词迅速在全球范围内流行起来。作为一种去中心化的共识达成机制，区块链上面不仅仅可以承载交易，还可以承载任何与价值相关的信息。<br />\r\n&nbsp; &nbsp; 尤其是进入2017年来，区地链成为互联网金融领域最热的领域，被认为是继大型机、个人电脑、互联网、移动社交网络之后计算方式的第五次颠覆创新，是人类信用进化史上继血亲信用、贵金属信用、央行纸币信用之后的第四个里程碑。区块链技术是下一代云计算的邹形，有望像互联网一样彻底重塑人类社会活动形态，关实现从目前的信息互联网向价值互联网的转变。其中以典型代表的BTC,ETH,LTC,DASH等数字货币的研究与应用也呈现出爆发式增长态势。<br />\r\n&nbsp; &nbsp; 随着人们对个人隐私的愈发重视，大家发现大多数现有的加密货币，包抱比特币和以太坊，都拥有透明的区块链，这意味着世界上任何人都可以公开验证和追踪交易。此外这些交易的发送和链接地址可能可以链接到一个人的真实世界的身份。这足以影响自己的人身安全。<br />\r\n&nbsp; &nbsp; 莱特币加密货币是一款点对点的分布式网络货币系统。她可以在瞬间，以低廉交易费的方式向全世界的任意角落付款。莱特币是一个开源的项目，全球性的支付网络，她没有任何的中央控制节点。严谨的密码学协议使这个网络系统充分保障每一位用户的财务安全。莱特币相对比特币的加密货币系统，拥有更快的交易确认时间，更高的网络交易容量和效率。莱特币现在拥有完整的产业链，充分的流动性，足以证明其是成熟、安全、稳定的商用金融系统。<br />\r\n&nbsp; &nbsp; &nbsp;它基于比特币(Bitcoin)协议，但不同于比特币的地方在于，即使在现阶段，通过消费级的硬件也可以高效地“挖矿”。Litecoin提供给您更快速的交易确认（平均2.5分钟），它使用硬内存以及基于scrypt(一种加密算法)的挖矿工作量证明算法，面向大多数人使用的普通计算机及图形处理器(GPU)。Litecoin网络预期将生产8400万个货币单位。<br />\r\n&nbsp; &nbsp; &nbsp;Litecoin的设计目的之一是提供一种挖掘算法，使它能够在挖掘比特币的机器上被同时运行。为挖掘比特币而设计的专用集成电路(ASIC)逐渐兴起的同时，Litecoin也紧跟着技术演变。但在Litecoin货币被广泛应用之前，不太可能会出现专门为Litecoin设计的专用集成电路(ASIC)。至此为了让莱特币翻倍升值在全球范围内得到广泛应用，团队重金打造锁仓计划，让用户把手里的LTC进行不同周期的锁仓，这样每天会获得高额的锁仓奖励，假设拿100个LTC进行锁仓六个月，每天可获得2个LTC奖励，奖励每天都可以进行交易，六个月之后一共可以获得360个LTC,高额的锁仓必将让越来越多的用户选择锁仓，随着LTC的用户不断增加，锁仓的数量越来越多，那么市场流通的币越来越少，价格就会持续一路上涨。<br />\r\n用户增加5倍，市场LTC数量减少2倍，即可实现LTC的10倍增长，若矿池这期间总共挖出500万枚，按现价150美金的单价来计算，本来只值7.5亿美金的LTC,通过送出200万枚作为锁仓奖励，让价格涨到880美金一枚，那么总价值就是60亿美金<br />\r\n<br />\r\n<br />\r\nIn recent years, with the rise and development of Bitcoin, the underlying technology \"blockchain\" has quickly gained popularity worldwide. As a decentralized consensus reach mechanism, the blockchain can not only carry transactions but also carry any value-related information.<br />\r\n????In particular, since 2017, the regional chain has become the hottest area in the Internet finance field. It is considered to be the fifth subversion innovation after the mainframe, personal computer, Internet, and mobile social networks. It is the blood of the evolution of human credit. The fourth milestone after credit, precious metal credits, central banknote credits. Blockchain technology is the next generation of cloud computing, and it is expected to completely reshape human social activities like the Internet, and realize the transition from the current information Internet to the value Internet. The research and application of representative digital currencies such as BTC, ETH, LTC, and DASH also showed an explosive growth trend.<br />\r\n????As people pay more and more attention to personal privacy, people find that most existing cryptocurrencies, including Bitcoin and Ethereum, have transparent blockchains, which means that anyone in the world can verify and track publicly. transaction. In addition, the sending and linking addresses of these transactions may be linked to a person\'s real-world identity. This is enough to affect your personal safety.<br />\r\n????Litecoin cryptocurrency is a point-to-point distributed network currency system. She can make payments to any corner of the world in an instant, at a low transaction fee. Litecoin is an open source project, global payment network, she does not have any central control node. Rigorous cryptographic protocols allow this network system to fully protect the financial security of each user. Litecoin has a faster transaction confirmation time and higher network transaction capacity and efficiency than bitcoin\'s cryptocurrency system. Litecoin now has a complete industrial chain and sufficient liquidity to prove that it is a mature, secure and stable commercial financial system.<br />\r\n?????It is based on the Bitcoin protocol, but unlike Bitcoin, the point is that even at this stage, consumer-grade hardware can efficiently “mine”. Litecoin provides you with faster transaction confirmation (averaging 2.5 minutes) using hard memory and a mining workload proof algorithm based on scrypt (a cryptographic algorithm) that targets the average computer and graphics processor (GPU) used by most people. ). The Litecoin network is expected to produce 84 million currency units.<br />\r\n?????One of Litecoin\'s design goals was to provide a mining algorithm that could be run simultaneously on Bitcoin mining machines. While ASICs designed to tap Bitcoin are gradually emerging, Litecoin is also closely following the evolution of technology. But before Litecoin\'s currency is widely used, there is unlikely to be an application-specific integrated circuit (ASIC) designed specifically for Litecoin. At this point, in order to allow Litecoin to double the appreciation of its value on a global scale, the team has built a lock-in plan, allowing the user to lock the LTC in different cycles so that each day will receive a large amount of locks, assuming 100 Each LTC locks up for six months and receives 2 LTC rewards per day. The rewards can be traded every day. A total of 360 LTCs can be obtained after six months. High lockouts will allow more and more users to choose. Locked up, as the number of users of LTC is increasing and the number of locks is increasing, the market circulation of coins will be less and the price will continue to rise.<br />\r\nWhen users increase 5 times, the number of LTCs in the market will decrease by 2 times, and the LTC will grow by 10 times. If the mine pool dug out 5 million pieces in total during this period, the LTC would have been worth only 750 million U.S. dollars at the current unit price of 150 U.S. dollars. , By sending 2 million as a lock-in reward, let the price rise to 880 US dollars, then the total value is 6 billion US dollars<br />\r\n<div>\r\n\t<br />\r\n</div>',1,1,1529917236,1529917236),(9,'LTC简介','LTC简介fv6ed5f16se5f1as6ef5a6sef51as16efwef123',2,1,1529917236,1529917236),(10,NULL,'Lfzd1Q8g7bkuoKTt5Cm9oeE8ZSpVZvyLSt',4,1,1530692895,1530692895),(11,'安卓地址','http://www.baidu.com',5,1,1529917236,1529917236),(12,'IOS地址','压死dfgvdsvgdszfv',6,1,1529917236,1529917236),(13,'提币申请控制开关',NULL,7,1,1529917236,1529917236);
/*!40000 ALTER TABLE `think_message` ENABLE KEYS */;

#
# Structure for table "think_news"
#

DROP TABLE IF EXISTS `think_news`;
CREATE TABLE `think_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(255) DEFAULT 'LTC平台' COMMENT '作者',
  `content` text COMMENT '内容',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID，若为0则表示所有用户',
  `is_del` int(10) NOT NULL DEFAULT '0' COMMENT '是否删除；（删除时间戳）',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='消息';

#
# Data for table "think_news"
#

/*!40000 ALTER TABLE `think_news` DISABLE KEYS */;
INSERT INTO `think_news` VALUES (1,'LTC平台即将全面上线','LTC平台','LTC平台即将全面上线，锁仓计划俱乐部，欢迎您的到来',0,0,1530871599,1530871599),(2,'感谢您成为平台第一位用户','LTC平台','感谢您成为平台第一位用户，您可以与平台联系，我们将有一份好礼送给您',3,0,1530871676,1530871676);
/*!40000 ALTER TABLE `think_news` ENABLE KEYS */;

#
# Structure for table "think_plan"
#

DROP TABLE IF EXISTS `think_plan`;
CREATE TABLE `think_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` smallint(5) DEFAULT '1' COMMENT '锁仓时间',
  `ratio` float(4,2) NOT NULL DEFAULT '0.00' COMMENT '锁仓利率',
  `number` smallint(6) DEFAULT '0' COMMENT '份额数',
  `start_time` int(10) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(10) DEFAULT NULL COMMENT '结束时间',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态；0=》关闭；1=》启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='锁仓计划';

#
# Data for table "think_plan"
#

/*!40000 ALTER TABLE `think_plan` DISABLE KEYS */;
INSERT INTO `think_plan` VALUES (1,1,1.00,888,1529888400,1529899200,1),(2,30,1.50,888,1529899200,1529910000,1),(3,90,2.00,888,1529910000,1529920800,1),(4,180,2.50,888,1529920800,1529931600,1);
/*!40000 ALTER TABLE `think_plan` ENABLE KEYS */;

#
# Structure for table "think_price"
#

DROP TABLE IF EXISTS `think_price`;
CREATE TABLE `think_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` float(6,2) NOT NULL DEFAULT '0.00' COMMENT 'LTC单价',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='LTC单价';

#
# Data for table "think_price"
#

/*!40000 ALTER TABLE `think_price` DISABLE KEYS */;
INSERT INTO `think_price` VALUES (1,2.00,1529909435,1529909435),(2,2.20,1529912229,1529912229),(3,2.40,1529912280,1529912280),(5,2.30,1529913621,1529913621),(6,1.80,1529914164,1529914164),(7,3.00,1529914180,1529914180),(8,2.80,1529914209,1529914209);
/*!40000 ALTER TABLE `think_price` ENABLE KEYS */;

#
# Structure for table "think_recharge"
#

DROP TABLE IF EXISTS `think_recharge`;
CREATE TABLE `think_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `recharge_id` varchar(255) DEFAULT NULL COMMENT '交易ID',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '充币数量',
  `state` tinyint(3) DEFAULT '0' COMMENT '充币状态；0=》审核中；1=》已通过；2=》已驳回',
  `talk` text COMMENT '反馈',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='充币记录';

#
# Data for table "think_recharge"
#

/*!40000 ALTER TABLE `think_recharge` DISABLE KEYS */;
INSERT INTO `think_recharge` VALUES (1,3,'SF6SF16ASDE5F1W6EFC1S6F1W6SEF1W56F',5,0,NULL,1530785476,1530785476),(2,3,'6S52F6F16WE5F16ASFC6SF1WE6FW6SF1W6EF',9,1,'LTC币已充至你的帐户',1530785494,1530785494);
/*!40000 ALTER TABLE `think_recharge` ENABLE KEYS */;

#
# Structure for table "think_trade"
#

DROP TABLE IF EXISTS `think_trade`;
CREATE TABLE `think_trade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trade_id` varchar(15) DEFAULT NULL COMMENT '交易订单编号ID',
  `buy_id` int(11) DEFAULT NULL COMMENT '买方用户ID',
  `sell_id` int(11) DEFAULT NULL COMMENT '卖方用户ID',
  `number` int(11) DEFAULT NULL COMMENT '交易数量',
  `ltc_price` varchar(15) DEFAULT NULL COMMENT '交易LTC价格',
  `count_price` varchar(20) DEFAULT NULL COMMENT '交易总价',
  `service_price` varchar(20) DEFAULT NULL COMMENT '手续费',
  `remit_state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '汇款状态；0=》待汇款；1=》已汇款',
  `trade_state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '交易状态：0=》未交易；1=》交易中；2=》待确认；3=》已完成；4=》已失效',
  `trade_time` int(10) DEFAULT NULL COMMENT '交易创建时间',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`buy_id`,`sell_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='交易订单';

#
# Data for table "think_trade"
#

/*!40000 ALTER TABLE `think_trade` DISABLE KEYS */;
INSERT INTO `think_trade` VALUES (1,'LTC1530787716',3,4,5,'2.8','14','0.15',1,3,1530787716,1530786064,1530787716),(2,'LTC1530789118',3,6,7,'2.8','19.6','0.21',1,3,1530789118,1530788986,1530789118);
/*!40000 ALTER TABLE `think_trade` ENABLE KEYS */;

#
# Structure for table "think_user"
#

DROP TABLE IF EXISTS `think_user`;
CREATE TABLE `think_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(11) NOT NULL DEFAULT '' COMMENT '会员编号',
  `phone` bigint(11) NOT NULL DEFAULT '0' COMMENT '手机号码',
  `portrait` varchar(255) DEFAULT NULL COMMENT '头像',
  `pwd_login` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码',
  `pwd_trade` varchar(255) NOT NULL DEFAULT '' COMMENT '交易密码',
  `share_id` varchar(8) NOT NULL DEFAULT '' COMMENT '分享ID',
  `p_id` varchar(8) DEFAULT NULL COMMENT '邀请人share_id',
  `sign_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '签到状态；1=》可签到；0=》签到结束',
  `sign_time` int(10) DEFAULT NULL COMMENT '最近一次签到时间',
  `asset_avali` float(6,4) NOT NULL DEFAULT '0.0000' COMMENT '可用资产',
  `asset_fixed` int(11) NOT NULL DEFAULT '0' COMMENT '固定资产',
  `bank` varchar(255) DEFAULT NULL COMMENT '开户行',
  `name` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `bank_num` varchar(30) DEFAULT NULL COMMENT '银行帐户',
  `bank_address` varchar(255) DEFAULT NULL COMMENT '开户行地址',
  `alipay` varchar(255) DEFAULT NULL COMMENT '支付宝帐号',
  `recharge_address` varchar(255) DEFAULT NULL COMMENT '充币地址',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '帐户状态；0=》禁用；1=》正常',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`number`,`phone`,`share_id`,`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户';

#
# Data for table "think_user"
#

/*!40000 ALTER TABLE `think_user` DISABLE KEYS */;
INSERT INTO `think_user` VALUES (1,'LTC0000001',13800000000,'/static/index/images/head.png','987d54a81487723d84901dfbad79dbda','987d54a81487723d84901dfbad79dbda','LTC00001',NULL,1,NULL,0.0244,0,NULL,'LTC平台',NULL,NULL,NULL,NULL,1,1530780749,1530780749),(3,'LTC0781916',18297905431,'\\uploads/20180705\\9e1ae7b4e1ed516b0e96e94dada826bd.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','MU2P7ZUZ','LTC00001',1,1531129005,38.1740,5,'中国工商银行','李权','6216616302100000001','合肥政务区支行','','SDGVDFGVDGV16ER5G1V9ER4GSD5G1VSDRG41SER65G1SERGS6E5RG1E6SR5G1ESR6SG5ES6R5G1ES6RG5ES6R5G1E6R5G1E6R1G6ERG1ER65G1ER65G1E6RG',1,1530781916,1531396743),(4,'LTC0782371',13564078415,'/static/index/images/head.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','A94VHWU2','MU2P7ZUZ',1,NULL,21.0060,9,'中国招商银行','孙越','6222021001098691378','合肥望江路支行','','',1,1530782371,1530787701),(5,'LTC0782436',13564078410,'/static/index/images/head.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','VESDEW62','MU2P7ZUZ',1,NULL,0.0000,0,NULL,NULL,NULL,NULL,NULL,NULL,1,1530782436,1530782436),(6,'LTC0782522',17333007330,'\\uploads/20180705\\78f5ae37172d5a26ed37e847776e013c.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','28L5PXHZ','A94VHWU2',1,NULL,35.8300,2,'中国平安银行','方罗','6222021001098691378','明珠广场支行','','',1,1530782522,1530789108),(7,'LTC0782564',17333007331,'/static/index/images/head.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','2U1BVFP9','A94VHWU2',1,NULL,0.0000,0,NULL,NULL,NULL,NULL,NULL,NULL,1,1530782564,1530782564),(8,'LTC0782609',17333007332,'/static/index/images/head.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','XQ98W878','VESDEW62',1,NULL,21.0000,0,'','','','','','',1,1530782609,1530783383),(9,'LTC0783106',13805517651,'/static/index/images/head.png','96e79218965eb72c92a549dd5a330112','96e79218965eb72c92a549dd5a330112','WBDL3BQG','28L5PXHZ',1,NULL,32.0000,0,'','','','','','',1,1530783106,1530783394);
/*!40000 ALTER TABLE `think_user` ENABLE KEYS */;

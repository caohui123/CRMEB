<template>
	<view class="new-users copy-data" :style="{height:pageHeight}">
		<view class="top" :style="colorStyle">
			<!-- #ifdef MP || APP-PLUS -->
			<view class="sys-head">
				<view class="sys-bar" :style="{height:sysHeight}"></view>
				<!-- #ifdef MP -->
				<view class="sys-title" :style="member_style==3?'color:#333':''">个人中心</view>
				<!-- #endif -->
				<view class="bg" :style="member_style==3?'background:#f5f5f5':''"></view>
			</view>
			<!-- #endif -->
		</view>
		<view class="mid" style="flex:1;overflow: hidden;" :style="colorStyle">
			<scroll-view scroll-y="true" style="height: 100%;">
				<view class="head">
					<view class="user-card" :class="member_style==3?'unBg':''">
						<view class="bg"></view>
						<view class="user-info">
							<view>
								<!-- 注释这个是加的bnt -->
								<!-- #ifdef H5 -->
								<button class="bntImg" v-if="userInfo.is_complete == 0 && isWeixin"
									@click="getWechatuserinfo">
									<image class="avatar" src='/static/images/f.png'></image>
									<view class="avatarName">获取头像</view>
								</button>
								<!-- #endif -->
								<!-- #ifdef MP -->
								<button class="bntImg" v-if="userInfo.is_complete == 0" @tap="getUserProfile">
									<image class="avatar" src='/static/images/f.png'></image>
									<view class="avatarName">获取头像</view>
								</button>
								<!-- #endif -->
								<!-- #ifndef APP-PLUS -->
								<view v-else class="avatar-box" :class="{on:userInfo.is_money_level}">
									<image class="avatar" :src='userInfo.avatar' v-if="userInfo.avatar"
										@click="goEdit()">
									</image>
									<image v-else class="avatar" src="/static/images/f.png" mode="" @click="goEdit()">
									</image>
									<view class="headwear" v-if="userInfo.is_money_level && userInfo.svip_open">
										<image src="/static/images/headwear.png"></image>
									</view>
								</view>
								<!-- #endif -->
								<!-- #ifdef APP-PLUS -->
								<view class="avatar-box" :class="{on:userInfo.is_money_level}">
									<image class="avatar" :src='userInfo.avatar' v-if="userInfo.avatar"
										@click="goEdit()">
									</image>
									<image v-else class="avatar" src="/static/images/f.png" mode="" @click="goEdit()">
									</image>
									<view class="headwear" v-if="userInfo.is_money_level && userInfo.svip_open">
										<image src="/static/images/headwear.png"></image>
									</view>
								</view>
								<!-- #endif -->
							</view>
							<view class="info">
								<!-- #ifdef MP -->
								<view class="name" v-if="!userInfo.uid" @click="openAuto"
									style="height: 100%; display: flex; align-items: center;">
									请点击授权
								</view>
								<!-- #endif -->
								<!-- #ifdef H5 -->
								<view class="name" v-if="!userInfo.uid && isWeixin" @click="openAuto"
									style="height: 100%; display: flex; align-items: center;">
									请点击授权
								</view>
								<!-- #endif -->
								<view class="name" v-if="userInfo.uid">
									{{userInfo.nickname}}
									<image class="live" :src="userInfo.vip_icon" v-if="userInfo.vip_icon"></image>
								</view>
								<view class="num" v-if="userInfo.phone" @click="goEdit()">
									<view class="num-txt">ID：{{userInfo.uid}}</view>
									<!-- <view class="icon">
										<image src="/static/images/edit.png" mode=""></image>
									</view> -->
								</view>
								<view class="phone" v-if="!userInfo.phone && isLogin" @tap="bindPhone">绑定手机号</view>
							</view>
							<view class="message">
								<navigator url="/pages/users/message_center/index" hover-class="none">
									<view v-if="userInfo.service_num" class="num">
										{{userInfo.service_num >= 100 ? '99+' : userInfo.service_num}}
									</view>
									<view class="iconfont icon-s-kefu"></view>
								</navigator>
							</view>
							<!-- #ifdef MP -->
							<!-- <view class="setting" @click="Setting"><text class="iconfont icon-shezhi"></text></view> -->
							<!-- #endif -->

						</view>
						<view class="num-wrapper">
							<view class="num-item" v-if="userInfo.balance_func_status"
								@click="goMenuPage('/pages/users/user_money/index')">
								<text class="num">{{userInfo.now_money || 0}}</text>
								<view class="txt">余额</view>
							</view>
							<view class="num-item" v-else
								@click="goMenuPage('/pages/users/user_goods_collection/index')">
								<text class="num">{{userInfo.collectCount || 0}}</text>
								<view class="txt">收藏</view>
							</view>
							<view class="num-item" @click="goMenuPage('/pages/users/user_integral/index')">
								<text class="num">{{userInfo.integral || 0}}</text>
								<view class="txt">积分</view>
							</view>
							<view class="num-item" @click="goMenuPage('/pages/users/user_coupon/index')">
								<text class="num">{{userInfo.couponCount || 0}}</text>
								<view class="txt">优惠券</view>
							</view>
						</view>
					</view>
					<view class="order-wrapper" :class="userInfo.svip_open?'':'height'">
						<view class="order-hd flex">
							<view class="left">订单中心</view>
							<navigator class="right flex" hover-class="none" url="/pages/users/order_list/index"
								open-type="navigate">
								查看全部
								<text class="iconfont icon-xiangyou"></text>
							</navigator>
						</view>
						<view class="order-bd">
							<block v-for="(item,index) in orderMenu" :key="index">
								<navigator class="order-item" hover-class="none" :url="item.url">
									<view class="pic">
										<!-- <image :src="item.img" mode=""></image> -->
										<text class="iconfont" :class="item.img"></text>
										<text class="order-status-num" v-if="item.num > 0">{{ item.num }}</text>
									</view>
									<view class="txt">{{item.title}}</view>
								</navigator>
							</block>
						</view>
					</view>
				</view>
				<!-- 轮播 -->
				<view class="slider-wrapper" v-if="imgUrls.length>0 && my_banner_status">
					<swiper indicator-dots="true" :autoplay="autoplay" :circular="circular" :interval="interval"
						:duration="duration" indicator-color="rgba(255,255,255,0.6)" indicator-active-color="#fff">
						<block v-for="(item,index) in imgUrls" :key="index">
							<swiper-item>
								<navigator @click="goMenuPage(item.url)"
									class='slide-navigator acea-row row-between-wrapper' hover-class='none'>
									<image :src="item.pic" class="slide-image"></image>
								</navigator>
							</swiper-item>
						</block>
					</swiper>
				</view>
				<!-- 会员菜单 -->
				<view class="user-menus" style="margin-top: 20rpx;">
					<view class="menu-title">我的服务</view>
					<view class="list-box">
						<!-- #ifdef APP-PLUS || H5 -->
						<block v-for="(item,index) in MyMenus" :key="index">
							<view class="item" v-if="item.url!='#' && item.url!='/pages/service/index'"
								@click="goMenuPage(item.url, item.name)">
								<image :src="item.pic"></image>
								<text>{{item.name}}</text>
							</view>
						</block>
						<!-- #endif -->
						<!-- #ifdef MP -->
						<block v-for="(item,index) in MyMenus" :key="index">
							<view class="item" v-if="item.url!='#' 
								&& item.url!='/pages/service/index' 
								&& item.url!='/pages/customer_list/chat' 
								|| (item.url=='/pages/customer_list/chat' && routineContact == 0)" @click="goMenuPage(item.url, item.name)">
								<image :src="item.pic"></image>
								<text>{{item.name}}</text>
							</view>
						</block>
						<button class="item" open-type='contact' v-if="routineContact == 1">
							<image src="/static/images/contact.png"></image>
							<text>联系客服</text>
						</button>
						<!-- #endif -->
						<!-- #ifdef APP-PLUS -->
						<view class="item" hover-class="none" @click="goMenuPage('/pages/users/privacy/index')">
							<image src="/static/images/menu.png"></image>
							<text>隐私协议</text>
						</view>
						<!-- #endif -->
					</view>
				</view>
				<view class="user-menus" style="margin-top: 20rpx;" v-if="storeMenu.length">
					<view class="menu-title">商家管理</view>
					<view class="list-box">
						<block v-for="(item,index) in storeMenu" :key="index">
							<view class="item" :url="item.url" hover-class="none"
								v-if="item.url!='#' && item.url!='/pages/service/index'"
								@click="goMenuPage(item.url, item.name)">
								<image :src="item.pic"></image>
								<text>{{item.name}}</text>
							</view>
						</block>
					</view>
				</view>
				<image src="/static/images/support.png" alt="" class='support'></image>
				<view class="uni-p-b-98"></view>
			</scroll-view>
		</view>
		<tabBar :pagePath="'/pages/user/index'"></tabBar>
	</view>
</template>
<script>
	let sysHeight = uni.getSystemInfoSync().statusBarHeight + 'px';
	import {
		getMenuList,
		getUserInfo,
		setVisit,
		updateUserInfo
	} from '@/api/user.js';
	import {
		wechatAuthV2,
		getNavigation,
		silenceAuth
	} from '@/api/public.js'
	import {
		toLogin
	} from '@/libs/login.js';
	import {
		mapState,
		mapGetters
	} from "vuex";
	// #ifdef H5
	import Auth from '@/libs/wechat';
	// #endif
	const app = getApp();
	import dayjs from '@/plugin/dayjs/dayjs.min.js';
	import Routine from '@/libs/routine';
	import colors from '@/mixins/color';
	import tabBar from "@/pages/index/components/tabBar.vue";
	import {
		getCustomer
	} from '@/utils/index.js'
	export default {
		components: {
			tabBar,
		},
		// computed: mapGetters(['isLogin','cartNum']),
		computed: {
			...mapGetters({
				cartNum: 'cartNum',
				isLogin: 'isLogin'
			})
		},
		filters: {
			coundTime(val) {
				var setTime = val * 1000
				var nowTime = new Date()
				var rest = setTime - nowTime.getTime()
				var day = parseInt(rest / (60 * 60 * 24 * 1000))
				// var hour = parseInt(rest/(60*60*1000)%24) //小时
				return day + '天'
			},
			dateFormat: function(value) {
				return dayjs(value * 1000).format('YYYY-MM-DD');
			}
		},
		mixins: [colors],
		data() {
			return {
				storeMenu: [], // 商家管理
				orderMenu: [{
						img: 'icon-daifukuan',
						title: '待付款',
						url: '/pages/users/order_list/index?status=0'
					},
					{
						img: 'icon-daifahuo',
						title: '待发货',
						url: '/pages/users/order_list/index?status=1'
					},
					{
						img: 'icon-daishouhuo',
						title: '待收货',
						url: '/pages/users/order_list/index?status=2'
					},
					{
						img: 'icon-daipingjia',
						title: '待评价',
						url: '/pages/users/order_list/index?status=3'
					},
					{
						img: 'icon-a-shouhoutuikuan',
						title: '售后/退款',
						url: '/pages/users/user_return_list/index'
					},
				],
				imgUrls: [],
				autoplay: true,
				circular: true,
				interval: 3000,
				duration: 500,
				isAuto: false, //没有授权的不会自动授权
				isShowAuth: false, //是否隐藏授权
				orderStatusNum: {},
				userInfo: {},
				MyMenus: [],
				sysHeight: sysHeight,
				mpHeight: 0,
				showStatus: 1,
				newData: {},
				activeRouter: '',
				// #ifdef H5 || MP
				pageHeight: '100%',
				routineContact: 0,
				// #endif
				// #ifdef APP-PLUS
				pageHeight: app.globalData.windowHeight,
				// #endif
				// #ifdef H5
				isWeixin: Auth.isWeixin(),
				//#endif
				footerSee: false,
				member_style: 1,
				my_banner_status: 1
			}
		},
		created() {
			uni.hideTabBar()
			let that = this;
			// if (uni.getStorageSync('FOOTER_BAR')) {
			// 	this.footerSee = true
			// 	uni.hideTabBar()
			// } else {

			// }
			// #ifdef MP
			// 小程序静默授权
			if (!this.$store.getters.isLogin) {
				Routine.getCode()
					.then(code => {
						Routine.silenceAuth(code).then(res => {
							this.onLoadFun();
						})
					})
					.catch(res => {
						uni.hideLoading();
					});
			}
			// #endif
			// #ifdef H5 || APP-PLUS
			if (that.isLogin == false) {
				toLogin()
			}
			// #endif
			// #ifdef APP-PLUS
			that.$set(that, 'pageHeight', app.globalData.windowHeight);
			// #endif

			let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
			let curRoute = routes[routes.length - 1].route //获取当前页面路由
			this.activeRouter = '/' + curRoute

		},
		onReady() {
			let self = this
			// #ifdef MP
			let info = uni.createSelectorQuery().select(".sys-head");
			info.boundingClientRect(function(data) { //data - 各种参数
				self.mpHeight = data.height
			}).exec()
			// #endif
		},
		onShow: function() {
			let that = this;
			// #ifdef APP-PLUS
			uni.getSystemInfo({
				success: function(res) {
					that.pageHeight = res.windowHeight + 'px'
				}
			});
			// #endif
			if (that.isLogin) {
				this.getUserInfo();
				this.getMyMenus();
				this.setVisit();
			};
		},
		methods: {
			getWechatuserinfo() {
				//#ifdef H5
				Auth.isWeixin() && Auth.oAuth('snsapi_userinfo', '/pages/user/index');
				//#endif
			},
			getRoutineUserInfo(e) {
				updateUserInfo({
					userInfo: e.detail.userInfo
				}).then(res => {
					this.getUserInfo();
					return this.$util.Tips('更新用户信息成功');
				}).catch(res => {

				})
			},
			// 记录会员访问
			setVisit() {
				setVisit({
					url: '/pages/user/index'
				}).then(res => {})
			},
			// 打开授权
			openAuto() {
				toLogin();
			},
			// 授权回调
			onLoadFun() {
				this.getUserInfo();
				this.getMyMenus();
				this.setVisit();
			},
			Setting: function() {
				uni.openSetting({
					success: function(res) {}
				});
			},
			// 授权关闭
			authColse: function(e) {
				this.isShowAuth = e
			},
			// 绑定手机
			bindPhone() {
				uni.navigateTo({
					url: '/pages/users/user_phone/index'
				})
			},
			/**
			 * 获取个人用户信息
			 */
			getUserInfo: function() {
				let that = this;
				getUserInfo().then(res => {
					that.userInfo = res.data
					that.$store.commit("SETUID", res.data.uid);
					that.orderMenu.forEach((item, index) => {
						switch (item.title) {
							case '待付款':
								item.num = res.data.orderStatusNum.unpaid_count
								break
							case '待发货':
								item.num = res.data.orderStatusNum.unshipped_count
								break
							case '待收货':
								item.num = res.data.orderStatusNum.received_count
								break
							case '待评价':
								item.num = res.data.orderStatusNum.evaluated_count
								break
							case '售后/退款':
								item.num = res.data.orderStatusNum.refunding_count
								break
						}
					})
				});
			},
			//小程序授权api替换 getUserInfo
			getUserProfile() {
				uni.showLoading({
					title: '获取中'
				});
				let self = this;
				Routine.getUserProfile()
					.then(res => {
						Routine.getCode()
							.then(code => {
								let userInfo = res.userInfo;
								userInfo.code = code;
								userInfo.spread_spid = app.globalData.spid; //获取推广人ID
								userInfo.spread_code = app.globalData.code; //获取推广人分享二维码ID
								Routine.authUserInfo(userInfo)
									.then(res => {
										if (res.data.key !== undefined && res.data.key) {
											uni.hideLoading();
											self.authKey = res.data.key;
											self.isPhoneBox = true;
										} else {
											uni.hideLoading();
											let time = res.data.expires_time - self.$Cache.time();
											self.$store.commit('LOGIN', {
												token: res.data.token,
												time: time
											});
											this.getUserInfo()
										}
									})
									.catch(res => {
										uni.hideLoading();
										uni.showToast({
											title: res.msg,
											icon: 'none',
											duration: 2000
										});
									});
							})
							.catch(res => {
								uni.hideLoading();
							});
					})
					.catch(res => {
						uni.hideLoading();
					});
			},
			/**
			 * 
			 * 获取个人中心图标
			 */
			switchTab(order) {
				this.orderMenu.forEach((item, index) => {
					switch (item.title) {
						case '待付款':
							item.img = order.dfk
							break
						case '待发货':
							item.img = order.dfh
							break
						case '待收货':
							item.img = order.dsh
							break
						case '待评价':
							item.img = order.dpj
							break
						case '售后/退款':
							item.img = order.sh
							break
					}
				})
			},
			getMyMenus: function() {
				let that = this;
				// if (this.MyMenus.length) return;
				getMenuList().then(res => {
					let storeMenu = []
					let myMenu = []
					res.data.routine_my_menus.forEach((el, index, arr) => {
						if (el.url == '/pages/admin/order/index' || el.url ==
							'/pages/admin/order_cancellation/index' || el.name ==
							'客服接待') {
							storeMenu.push(el)
						} else {
							myMenu.push(el)
						}
					})
					this.member_style = Number(res.data.diy_data.value)
					this.my_banner_status = res.data.diy_data.my_banner_status
					let order01 = {
						dfk: 'icon-daifukuan',
						dfh: 'icon-daifahuo',
						dsh: 'icon-daishouhuo',
						dpj: 'icon-daipingjia',
						sh: 'icon-a-shouhoutuikuan'
					}
					let order02 = {
						dfk: 'icon-daifukuan-lan',
						dfh: 'icon-daifahuo-lan',
						dsh: 'icon-daishouhuo-lan',
						dpj: 'icon-daipingjia-lan',
						sh: 'icon-shouhou-tuikuan-lan'
					}
					let order03 = {
						dfk: 'icon-daifukuan-ju',
						dfh: 'icon-daifahuo-ju',
						dsh: 'icon-daishouhuo-ju',
						dpj: 'icon-daipingjia-ju',
						sh: 'icon-shouhou-tuikuan-ju'
					}
					let order04 = {
						dfk: 'icon-daifukuan-fen',
						dfh: 'icon-daifahuo-fen',
						dsh: 'icon-daishouhuo-fen',
						dpj: 'icon-daipingjia-fen',
						sh: 'icon-a-shouhoutuikuan-fen'
					}
					let order05 = {
						dfk: 'icon-daifukuan-lv',
						dfh: 'icon-daifahuo-lv',
						dsh: 'icon-daishouhuo-lv',
						dpj: 'icon-daipingjia-lv',
						sh: 'icon-shouhou-tuikuan-lv'
					}
					switch (res.data.diy_data.order_status) {
						case 1:
							this.switchTab(order01)
							break
						case 2:
							this.switchTab(order02)
							break
						case 3:
							this.switchTab(order03)
							break
						case 4:
							this.switchTab(order04)
							break
						case 5:
							this.switchTab(order05)
							break
					}
					that.$set(that, 'MyMenus', myMenu);
					that.$set(that, 'storeMenu', storeMenu);
					this.imgUrls = res.data.routine_my_banner
					this.routineContact = Number(res.data.routine_contact_type)
				});
			},
			// 编辑页面
			goEdit() {
				if (this.isLogin == false) {
					toLogin();
				} else {
					uni.navigateTo({
						url: '/pages/users/user_info/index'
					})
				}

			},
			// 签到
			goSignIn() {
				uni.navigateTo({
					url: '/pages/users/user_sgin/index'
				})
			},
			// goMenuPage
			goMenuPage(url, name) {
				if (this.isLogin) {
					if (url.indexOf('http') === -1) {
						// #ifdef H5
						if (name && name === '客服接待') {
							return uni.navigateTo({
								url: `/pages/annex/web_view/index?url=${location.origin}${url}`
							});
						} else if (name && name === '联系客服') {
							return getCustomer(url)
							
						} else if (name === '订单核销') {
							return window.location.href = `${location.origin}${url}`
						}
						// #endif
						// #ifdef MP
						if (url != '#' && url == '/pages/users/user_info/index') {
							uni.openSetting({
								success: function(res) {}
							});
						}
						// #endif
						uni.navigateTo({
							url: url
						})
					} else {
						uni.navigateTo({
							url: `/pages/annex/web_view/index?url=${url}`
						});
					}
				} else {
					// #ifdef MP
					this.openAuto()
					// #endif
				}
			},
			goRouter(item) {
				var pages = getCurrentPages();
				var page = (pages[pages.length - 1]).$page.fullPath;
				if (item.link == page) return
				uni.switchTab({
					url: item.link,
					fail(err) {
						uni.redirectTo({
							url: item.link
						})
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	page,
	body {
		height: 100%;
	}

	.height {
		margin-top: -100rpx !important;
	}

	.unBg {
		background-color: unset !important;

		.user-info {
			.info {
				.name {
					color: #333333 !important;
					font-weight: 600;
				}

				.num {
					color: #333 !important;

					.num-txt {
						height: 38rpx;
						background-color: rgba(51, 51, 51, 0.13);
						padding: 0 12rpx;
						border-radius: 16rpx;
					}
				}
			}
		}

		.num-wrapper {
			color: #333 !important;
			font-weight: 600;

			.num-item {
				.txt {
					color: rgba(51, 51, 51, 0.7) !important;
				}
			}
		}

		.message {
			.iconfont {
				color: #333 !important;
			}

			.num {
				color: #fff !important;
				background-color: var(--view-theme) !important;
			}
		}

		.setting {
			.iconfont {
				color: #333 !important;
			}
		}
	}

	.cardVipB {
		background-color: #343A48;
		width: 100%;
		height: 124rpx;
		border-radius: 16rpx 16rpx 0 0;
		padding: 22rpx 30rpx 0 30rpx;
		margin-top: 16px;

		.left-box {
			.small {
				color: #F8D5A8;
				font-size: 28rpx;
				margin-left: 18rpx;
			}

			.pictrue {
				width: 40rpx;
				height: 45rpx;

				image {
					width: 100%;
					height: 100%;
				}
			}
		}

		.btn {
			color: #BBBBBB;
			font-size: 26rpx;
		}

		.icon-xiangyou {
			margin-top: 6rpx;
		}
	}

	.cardVipA {
		position: absolute;
		background: url('~@/static/images/member.png') no-repeat;
		background-size: 100% 100%;
		width: 750rpx;
		height: 84rpx;
		bottom: -2rpx;
		left: 0;
		padding: 0 56rpx 0 135rpx;

		.left-box {
			font-size: 26rpx;
			color: #905100;
			font-weight: 400;
		}

		.btn {
			color: #905100;
			font-weight: 400;
			font-size: 24rpx;
		}

		.iconfont {
			font-size: 20rpx;
			margin: 4rpx 0 0 4rpx;
		}
	}

	.new-users {
		display: flex;
		flex-direction: column;
		height: 100%;

		.sys-head {
			position: relative;
			width: 100%;
			// background: linear-gradient(90deg, $bg-star1 0%, $bg-end1 100%);

			.bg {
				position: absolute;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				// background: url("~@/static/images/user-sys.png") no-repeat;
				background: var(--view-theme);
				background-size: 100% auto;
				background-position: left bottom;
			}

			.sys-title {
				z-index: 10;
				position: relative;
				height: 43px;
				text-align: center;
				line-height: 43px;
				font-size: 36rpx;
				color: #FFFFFF;
			}
		}

		.head {
			// background: #fff;

			.user-card {
				position: relative;
				width: 100%;
				height: 360rpx;
				margin: 0 auto;
				padding: 35rpx 28rpx;
				background-image: url("~@/static/images/user01.png");
				background-size: 100% auto;
				background-color: var(--view-theme);

				.user-info {
					z-index: 20;
					position: relative;
					display: flex;

					.headwear {
						position: absolute;
						right: -4rpx;
						top: -14rpx;
						width: 44rpx;
						height: 44rpx;

						image {
							width: 100%;
							height: 100%;
						}
					}

					.live {
						width: 28rpx;
						height: 28rpx;
						margin-left: 20rpx;
					}

					.bntImg {
						width: 120rpx;
						height: 120rpx;
						border-radius: 50%;
						text-align: center;
						line-height: 120rpx;
						background-color: unset;
						position: relative;

						.avatarName {
							font-size: 16rpx;
							color: #fff;
							text-align: center;
							background-color: rgba(0, 0, 0, 0.6);
							height: 37rpx;
							line-height: 37rpx;
							position: absolute;
							bottom: 0;
							left: 0;
							width: 100%;
						}
					}

					.avatar-box {
						position: relative;
						display: flex;
						align-items: center;
						justify-content: center;
						width: 120rpx;
						height: 120rpx;
						border-radius: 50%;

						&.on {
							.avatar {
								border: 2px solid #FFAC65;
								border-radius: 50%;
							}
						}
					}

					.avatar {
						position: relative;
						width: 120rpx;
						height: 120rpx;
						border-radius: 50%;

					}

					.info {
						flex: 1;
						display: flex;
						flex-direction: column;
						justify-content: space-between;
						margin-left: 20rpx;
						padding: 15rpx 0;

						.name {
							display: flex;
							align-items: center;
							color: #fff;
							font-size: 31rpx;

							.vip {
								margin-left: 10rpx;

								image {
									width: 78rpx;
									height: 30rpx;
									display: block;
								}
							}
						}

						.num {
							display: flex;
							align-items: center;
							font-size: 26rpx;
							color: rgba(255, 255, 255, 0.6);

							image {
								width: 22rpx;
								height: 23rpx;
								margin-left: 20rpx;
							}
						}
					}
				}

				.message {
					align-self: flex-start;
					position: relative;
					margin-top: 15rpx;
					margin-right: 20rpx;

					.num {
						position: absolute;
						top: -8rpx;
						left: 18rpx;
						padding: 0 6rpx;
						height: 28rpx;
						border-radius: 12rpx;
						background-color: #fff;
						font-size: 18rpx;
						line-height: 28rpx;
						text-align: center;
						color: var(--view-theme);
					}

					.iconfont {
						font-size: 40rpx;
						color: #fff;
					}
				}

				.num-wrapper {
					z-index: 30;
					position: relative;
					display: flex;
					align-items: center;
					justify-content: space-between;
					margin-top: 22rpx;
					// padding: 0 47rpx;
					color: #fff;

					.num-item {
						width: 33.33%;
						text-align: center;

						&~.num-item {
							position: relative;

							&:before {
								content: '';
								position: absolute;
								width: 1rpx;
								height: 28rpx;
								top: 50%;
								margin-top: -14rpx;
								background-color: rgba(255, 255, 255, 0.4);
								left: 0;
							}
						}

						.num {
							font-size: 42rpx;
							font-weight: bold;
						}

						.txt {
							margin-top: 8rpx;
							font-size: 26rpx;
							color: rgba(255, 255, 255, 0.6);
						}
					}
				}

				.sign {
					z-index: 200;
					position: absolute;
					right: -12rpx;
					top: 80rpx;
					display: flex;
					align-items: center;
					justify-content: center;
					width: 120rpx;
					height: 60rpx;
					background: linear-gradient(90deg, rgba(255, 225, 87, 1) 0%, rgba(238, 193, 15, 1) 100%);
					border-radius: 29rpx 4rpx 4rpx 29rpx;
					color: #282828;
					font-size: 28rpx;
					font-weight: bold;
				}
			}

			.order-wrapper {
				background: #fff;
				margin: 0 30rpx;
				border-radius: 16rpx;
				position: relative;
				margin-top: -80rpx;

				.order-hd {
					justify-content: space-between;
					padding: 30rpx 20rpx 10rpx 30rpx;
					margin-top: 25rpx;
					font-size: 30rpx;
					color: #282828;

					.left {
						font-weight: bold;
					}

					.right {
						display: flex;
						align-items: center;
						color: #666666;
						font-size: 26rpx;

						.icon-xiangyou {
							margin-left: 5rpx;
							font-size: 26rpx;
						}
					}
				}

				.order-bd {
					display: flex;
					padding: 0 0;

					.order-item {
						display: flex;
						flex-direction: column;
						justify-content: center;
						align-items: center;
						width: 20%;
						height: 140rpx;

						.pic {
							position: relative;
							text-align: center;

							.iconfont {
								font-size: 48rpx;
								color: var(--view-theme);
							}

							image {
								width: 58rpx;
								height: 48rpx;
							}
						}

						.txt {
							margin-top: 6rpx;
							font-size: 26rpx;
							color: #333;
						}
					}
				}
			}
		}

		.slider-wrapper {
			margin: 20rpx 30rpx;
			height: 130rpx;

			swiper,
			swiper-item {
				height: 100%;
			}

			image {
				width: 100%;
				height: 130rpx;
				border-radius: 16rpx;
			}
		}

		.user-menus {
			background-color: #fff;
			margin: 0 30rpx;
			border-radius: 16rpx;

			.menu-title {
				padding: 30rpx 30rpx 40rpx;
				font-size: 30rpx;
				color: #282828;
				font-weight: bold;
			}

			.list-box {
				display: flex;
				flex-wrap: wrap;
				padding: 0;
			}

			.item {
				position: relative;
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-direction: column;
				width: 25%;
				margin-bottom: 47rpx;
				font-size: 26rpx;
				color: #333333;

				image {
					width: 52rpx;
					height: 52rpx;
					margin-bottom: 18rpx;
				}


				&:last-child::before {
					display: none;
				}
			}

			button {
				font-size: 28rpx;
			}
		}

		.phone {
			color: #fff;
			background-color: #CCC;
			border-radius: 15px;
			width: max-content;
			padding: 0 10px;
		}

		.order-status-num {

			min-width: 12rpx;
			background-color: #fff;
			color: var(--view-theme);
			border-radius: 15px;
			position: absolute;
			right: -14rpx;
			top: -15rpx;
			font-size: 20rpx;
			padding: 0 8rpx;
			border: 1px solid var(--view-theme);
		}

		.support {
			width: 219rpx;
			height: 74rpx;
			margin: 54rpx auto;
			display: block;
		}
	}

	.card-vip {
		display: flex;
		align-items: center;
		justify-content: space-between;
		position: relative;
		width: 690rpx;
		height: 134rpx;
		margin: -72rpx auto 0;
		background-size: cover;
		padding-left: 118rpx;
		padding-right: 34rpx;

		.left-box {
			font-size: 24rpx;
			color: #AE5A2A;

			.big {
				font-size: 28rpx;
			}

			.small {
				opacity: 0.8;
				margin-top: 10rpx;
			}
		}

		.btn {
			height: 52rpx;
			line-height: 52rpx;
			padding: 0 20rpx;
			text-align: center;
			background: #fff;
			border-radius: 28rpx;
			font-size: 26rpx;
			color: #AE5A2A;
		}

	}

	.setting {
		margin-top: 15rpx;
		margin-left: 15rpx;
		color: #fff;

		.iconfont {
			font-size: 40rpx;
		}
	}

	.page-footer {
		position: fixed;
		bottom: 0;
		z-index: 30;
		display: flex;
		align-items: center;
		justify-content: space-around;
		width: 100%;
		height: calc(98rpx+ constant(safe-area-inset-bottom)); ///兼容 IOS<11.2/
		height: calc(98rpx + env(safe-area-inset-bottom)); ///兼容 IOS>11.2/
		box-sizing: border-box;
		border-top: solid 1rpx #F3F3F3;
		background-color: #fff;
		box-shadow: 0px 0px 17rpx 1rpx rgba(206, 206, 206, 0.32);
		padding-bottom: constant(safe-area-inset-bottom); ///兼容 IOS<11.2/
		padding-bottom: env(safe-area-inset-bottom); ///兼容 IOS>11.2/

		.foot-item {
			display: flex;
			width: max-content;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			position: relative;

			.count-num {
				position: absolute;
				display: flex;
				justify-content: center;
				align-items: center;
				width: 40rpx;
				height: 40rpx;
				top: 0rpx;
				right: -15rpx;
				color: #fff;
				font-size: 20rpx;
				background-color: #FD502F;
				border-radius: 50%;
				padding: 4rpx;
			}
		}

		.foot-item image {
			height: 50rpx;
			width: 50rpx;
			text-align: center;
			margin: 0 auto;
		}

		.foot-item .txt {
			font-size: 24rpx;


			&.active {}
		}
	}

	.new-users {
		padding-bottom: 0;
		padding-bottom: constant(safe-area-inset-bottom);
		padding-bottom: env(safe-area-inset-bottom);
	}
</style>

<template>
	<view>
		<view id="home" class="home acea-row row-center-wrapper"
			:class="[returnShow ? 'p10':'p20', text_opacity >= 1 ? 'opacity':'']" :style="{ top: homeTop +'rpx'}">
			<view v-if="returnShow" class="iconfont icon-xiangzuo" :class="text_opacity >= 1 ? 'opacity':''"
				@tap="returns">
			</view>

			<view class="line" v-if="returnShow" :class="text_opacity >= 1 ? 'opacity':''"></view>
			<view class="animation-box">
				<transition name="fade">
					<view v-if="!Active" class="iconfont icon-gengduo4" :class="text_opacity >= 1 ? 'opacity':''"
						@click="open">
					</view>
					<!-- 	<view v-if="Active" class="iconfont icon-guanbi5" @click="open">
					</view> -->
				</transition>
				<transition name="fade" mode="out-in">
					<!-- 	<view v-if="!Active" class="iconfont icon-gengduo4" @click="open">
					</view> -->
					<view v-if="Active" class="iconfont icon-guanbi5" :class="text_opacity >= 1 ? 'opacity':''"
						@click="open">
					</view>
				</transition>
			</view>
			<view class="homeCon bg-color" :class="Active === true ? 'active' : ''" v-if="Active" @click="open">
				<view class="homeCon-box" v-for="(item,index) in iconList" :key="index"
					@click="jumpUrl(item.path,item.jumpType)">
					<text class='iconfont' :class="item.iconName">
					</text>
					<text class="text">{{item.name}}</text>
				</view>

			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "menuIcon",
		data() {
			return {
				Active: false,
				returnShow: true, //判断顶部返回是否出现
				homeTop: 20,
				text_opacity: 0,
				iconList: [{
						name: '首页',
						iconName: "icon-shouye8",
						path: '/pages/index/index',
						jumpType: 1
					},
					{
						name: '购物车',
						iconName: "icon-gouwuche7",
						path: '/pages/order_addcart/order_addcart',
						jumpType: 1
					},
					{
						name: '搜索',
						iconName: "icon-sousuo6",
						path: '/pages/goods_search/index',
						jumpType: 0
					},
					{
						name: '收藏',
						iconName: "icon-shoucang3",
						path: '/pages/users/user_goods_collection/index',
						jumpType: 0
					},
					{
						name: '我的',
						iconName: "icon-yonghu1",
						path: '/pages/user/index',
						jumpType: 1
					},
					{
						name: '首页',
						iconName: "icon-shouye8",
						path: '/pages/index/index',
						jumpType: 1
					}
				]
			};
		},
		props: {
			showMenuIcon: {
				type: Boolean,
				default: false
			},
			opacity: {
				type: Number,
				default: 1
			}
		},
		watch: {
			showMenuIcon(e) {
				this.Active = e
			},
			opacity(e) {
				this.text_opacity = e
			}
		},
		mounted() {
			var pages = getCurrentPages();
			this.returnShow = pages.length === 1 ? false : true;
			this.$nextTick(() => {
				// #ifdef MP
				const menuButton = uni.getMenuButtonBoundingClientRect();
				const query = uni.createSelectorQuery().in(this);
				query
					.select('#home')
					.boundingClientRect(data => {
						this.homeTop = menuButton.top * 2 + menuButton.height - data.height;
					})
					.exec();
				// #endif
			});
		},
		methods: {
			open() {
				this.Active = !this.Active
				if (this.Active) this.$emit('open', true)
			},
			// 后退
			returns() {
				uni.navigateBack();
			},
			jumpUrl(url, type) {
				(type === 1 ? uni.switchTab : uni.navigateTo)({
					url
				})
			},
		}
	}
</script>

<style lang="scss">
	.home {
		/* #ifdef H5 */
		top: 20rpx !important;
		/* #endif */
	}

	.home.opacity {
		background: rgba(255, 255, 255, 1);
		border: 1px solid #f2f2f2;
	}

	.home {
		color: #fff;
		position: fixed;
		// width: 130rpx;
		// padding: 0 20rpx;
		height: 56rpx;
		z-index: 99;
		left: 33rpx;
		background: rgba(190, 190, 190, 0.5);
		border-radius: 30rpx;
		display: flex;
		align-items: center;

		.opacity {
			color: #050505;
		}

		.icon-gengduo4,
		.icon-guanbi5 {
			position: absolute;
			width: 40rpx;
		}

		.icon-gengduo4 {
			font-size: 28rpx;
			text-align: center;
		}
	}

	.home .animation-box {
		position: relative;
		width: 39rpx;
		height: 28rpx;
	}

	.icon-guanbi5 {
		font-size: 28rpx;
		text-align: center;
	}

	// .home .icon-gengduo4,
	// .home.close .icon-guanbi5 {
	// 	opacity: 1;
	// 	transform: scale(1);
	// }

	// .home.close .icon-gengduo4 {
	// 	opacity: 0;
	// 	transform: scale(0);
	// }

	.home .homeCon {
		display: flex;
		flex-direction: column;
		font-size: 26rpx;
		padding: 4rpx;
		opacity: 0;
		border: 1px solid #f2f2f2;

		&::before {
			content: "";
			width: 0;
			height: 0;
			border-left: 15rpx solid transparent;
			border-right: 15rpx solid transparent;
			border-bottom: 17rpx solid #fff;
			position: absolute;
			top: -15rpx;
			right: 66rpx;
			border-bottom-color: #f2f2f2;
		}

		&::after {
			content: "";
			width: 0;
			height: 0;
			border-left: 15rpx solid transparent;
			border-right: 15rpx solid transparent;
			border-bottom: 17rpx solid #fff;
			position: absolute;
			top: -13rpx;
			right: 66rpx;
		}

		.homeCon-box {
			display: flex;
			align-items: center;
			flex-wrap: nowrap;
			width: 100%;
			padding: 15rpx 18rpx;

			.text {
				display: flex;
				flex-wrap: nowrap;
			}
		}

		.iconfont {
			display: flex;
			align-items: center;
			padding: 5rpx 3rpx;
			margin-right: 5rpx;
		}

		.homeCon-box:nth-child(even) {
			border-top: 1px solid #f2f2f2;
			border-bottom: 1px solid #f2f2f2;
		}
	}

	.home .homeCon.active {
		position: absolute;
		opacity: 1;
		top: 75rpx;
		animation: bounceInLeft 0.5s cubic-bezier(0.215, 0.310, 0.655, 1.000);
		width: max-content;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 10rpx;
		color: #333;
		background: #fff !important;
		box-shadow: $uni-index-box-shadow;
	}

	.home .line {
		width: 2rpx;
		height: 24rpx;
		margin: 0 12rpx 0 10rpx;
		background: rgba(255, 255, 255, 0.25);
	}

	.home .line.opacity {
		color: #050505;
		background-color: #050505;
	}

	.home .icon-xiangzuo {
		font-size: 28rpx;
	}

	.fade-enter-active,
	.fade-leave-active {
		transition: opacity .3s;
	}

	.fade-enter,
	.fade-leave-to

	/* .fade-leave-active below version 2.1.8 */
		{
		opacity: 0;
	}

	.p10 {
		padding: 0 20rpx;
	}

	.p20 {
		padding: 0 40rpx;
	}
</style>

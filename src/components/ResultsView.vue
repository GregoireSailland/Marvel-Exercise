<template>
	<div class="rwesults-view">
		<!--div>{{opened_details}}</div-->
		<div v-for="(result,result_key) in results" :key="'result'+result_key" class="item card">
			<div class="type" v-if="result.type" v-html="result.type"></div>
			<h2 v-if="result.title" v-html="result.title"></h2>
			<h2 v-if="result.name" v-html="result.name"></h2>
			<h2 v-if="result.fullName" v-html="result.fullName"></h2>
			<div v-if="result.thumbnail" class="thumbnail">
				<img :src="result.thumbnail.path.replace('http://','https://')+'.'+result.thumbnail.extension" v-if="!result.thumbnail.path.includes('image_not_available')">
			</div>
			<div v-if="result.description" v-html="result.description" class="description"></div>
			<div v-if="result.originalIssue && result.originalIssue.name">
				Original issue:
				<a @click="_fetch(result.originalIssue,'')" class="link" v-html="result.originalIssue.name"></a>
			</div>


			<div v-for="(result_type,result_type_key) in results_types" :key="'result-type-'+result_type_key">
				<div v-if="result[result_type.name] && result[result_type.name].available" :class="result_type.name">
					<h3 @click="open_details(result_key,result_type_key)" class="link collapse" :class="{'collapse-open':opened_details && opened_details.result==result_key && opened_details.result_type==result_type_key}">{{result_type.name}} <span class="pill">{{result[result_type.name].available}}</span></h3>
					<div v-if="result[result_type.name].items && opened_details && opened_details.result==result_key && opened_details.result_type==result_type_key" class="items">
						
						<ul>
							<li v-if="result[result_type.name].returned && result[result_type.name].returned < result[result_type.name].available" class="all-results">
								<a @click="_fetch(result[result_type.name],get_result_title(result)+ '<br>' +result_type.name)" class="link">Show all</a>
							</li>
							<li v-for="(item,item_key) in result[result_type.name].items" :key="result_type.name+item_key">
								<a @click="_fetch(item, get_result_title(result)+ '<br>' +result_type.name)" v-html="item.name || 'item'" class="link"></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div style="max-width:100%;overflow:auto;" v-if="debug">
				<pre>{{Object.keys(result)}}</pre>
				<pre>{{result}}</pre>
			</div>
		</div>
	</div>
</template>
<script>

export default{
	props:{
		results:{
			type:Array,
			default:()=>[]
		}

	},
	data(){
		return{
			results_types:[
			{name:'characters',key:'character'},
			{name:'series',key:'serie'},
			{name:'creators',key:'creator'},
			{name:'comics',key:'comic'},
			{name:'stories',key:'story'},
			{name:'events',key:'event'},
			],
			opened_details:{
				result:null,
				result_type:null,
			},
		}
	},
	methods:{
		get_result_title(result){
			let title=[]
			if(result.title){
				title.push(result.title)
			}
			if(result.fullName){
				title.push(result.fullName)
			}
			else if(result.name){
				title.push(result.name)
			}
			return title.join(' - ')
		},
		open_details(result_key,result_type_key){
			this.opened_details.result=result_key
			this.opened_details.result_type=result_type_key
		},
		_fetch(item,endpoint=null){
			if(this.debug){
				console.log('fetching',endpoint,item)
			}
			if(endpoint){
				this.$store.commit({
					type: "set_current_endpoint",
					endpoint:'',
				})
				this.$store.commit({
					type: "set_last_endpoint",
					endpoint:endpoint,
				})
			}
			let url=''
			if(item.resourceURI){
				url=item.resourceURI
			}else if(item.collectionURI){
				url=item.collectionURI
			}
			if(url!=''){
				this.$emit('query',url)
			}
		}
	},
	computed:{
		debug(){
			return this.$store.state.debug;
		}
	}

}
</script>
<style>
.thumbnail{
	overflow:hidden;
}
.thumbnail img{
	transform:scale(1);
	transition:all 3s;
}
.thumbnail img:hover{
	transform:scale(1.5);
}
.rwesults-view{
	display:flex;flex-wrap:wrap;align-items: stretch;justify-content: center;
}
.rwesults-view>*{
	flex:1;
	min-width:300px;
	max-width:600px;
}
.collapse:after{
	margin-left:.5em;
	content:'';
	display:inline-block;
	vertical-align: middle;
	width: 14px;
	height: 24px;
	background-image:url(data:image/svg+xml,%0A%3Csvg%20aria-hidden%3D%22true%22%20focusable%3D%22false%22%20data-icon%3D%22angle-down%22%20role%3D%22img%22%20xmlns%3D%22http:%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20256%20512%22%3E%0A%0A%3Cpath%20fill%3D%22currentColor%22%20d%3D%22M119.5%20326.9L3.5%20209.1c-4.7-4.7-4.7-12.3%200-17l7.1-7.1c4.7-4.7%2012.3-4.7%2017%200L128%20287.3l100.4-102.2c4.7-4.7%2012.3-4.7%2017%200l7.1%207.1c4.7%204.7%204.7%2012.3%200%2017L136.5%20327c-4.7%204.6-12.3%204.6-17-.1z%22%3E%0A%0A%3C%2Fpath%3E%0A%0A%3C%2Fsvg%3E%0A);
	background-repeat:no-repeat;
	background-size:contain;
}
.collapse.collapse-open:after{
	transform:rotate(180deg);
}
.link{
	cursor:pointer;
}
.pill{
	display:inline-block;
	vertical-align:middle;
	font-size:.5em;
	background-color:#333;
	color:#fff;
	padding: 0.4em 0.6em;
	border-radius:50%;
}
h3:first-letter {
	text-transform: uppercase;
}
.card>.type{
	float:left;
	padding:10px;
	line-height:1;
	background-color:#333;
	color:#fff;
	margin-right:1em;
}
img{max-width:100%;height:auto;}
.card{
	box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
	transition: 0.3s;
	_border: 1px solid #333;
	border-radius: 5px;
	margin:1em;
}
.card:hover{
	box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.card>*{
	padding:0 1.5em;
	margin:1em 0;
}
.card>.thumbnail{
	padding:0;
}
.thumbnail img{
	width:100%;
}
.description{
	margin:1em 0;
}
.items ul{
	padding:0;
}
.items li{
	margin:1em 0;
}
</style>
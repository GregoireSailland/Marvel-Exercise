<template>
  <div class="home">
    <h1>Home</h1>
    <template v-if="loading">
      <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
      {{last_queried_url || 'are you looking for something?'}}
    </template>
    <template v-else>
      <div class="centered">
        <label class="endpoints">
          <div>Endpoints</div>
          <!--select :value="current_endpoint" @change="set_current_endpoint($event.target.value)">
            <option value="null"></option>
            <option v-for="(endpoint,endpoint_key) in endpoints" :key="endpoint_key" v-html="endpoint"></option>
          </select!-->
          <div class="nav">
            <a class="button" @click="set_current_endpoint(endpoint)" v-for="(endpoint,endpoint_key) in endpoints" :key="endpoint_key" v-html="endpoint"></a>
          </div>
        </label>
        <label v-if="current_endpoint">
          <div>Search</div>
          <input type="text" v-model="search" @keyup.enter="search!='' && _search(search)">
          <button v-if="search!=''" @click="_search(search)">
            <svg aria-hidden="true" focusable="false" data-icon="arrow-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"></path></svg>
          </button>
          
        </label>
      </div>
      <h1 v-if="last_endpoint" v-html="last_endpoint" class="uppercase"></h1>
      <div class="response" v-if="response">
        <div v-if="response.data && response.data.status && response.data.status.toLowerCase()=='ok'" class="padded">
          <div v-if="response.data.attributionHTML" v-html="response.data.attributionHTML"></div>
          <div v-else-if="response.data.attributionText">{{response.data.attributionText}}</div>
        </div>
        <div v-else>
          No results found
        </div> 

        <div :class="{'debug':debug}">
          <div v-if="response.data.data">

            <nav aria-label="Page navigation example" v-if="response && response.data && response.data.data && response.data.data.total && response.data.data.total/response.data.data.limit > 1">
              <ul class="pagination">
                <li class="page-items">
                  <button type="button" class="button page-link" v-if="page != 1" @click="query(last_queried_url,page-1)"> Previous </button>
                  <button type="button" class="button page-link" :class="{'current':pageNumber==page}" v-for="pageNumber in numPages" @click="query(last_queried_url,pageNumber)" :key="'page'+pageNumber"> {{pageNumber}} </button>
                  <button type="button" @click="query(last_queried_url,page+1)" v-if="page < response.data.data.total/response.data.data.limit" class="button page-link"> Next </button>
                </li>
              </ul>
            </nav>  

            <div v-if="response.data.data.count<response.data.data.total" class="padded">Showing {{response.data.data.count}} results on {{response.data.data.total}}</div>
              <div v-else class="padded">Showing all of {{response.data.data.total}} results</div>
              <ResultsView @query="(url)=>{/* set_current_endpoint('') &&  */ query(url)}" v-if="response.data.data.results" :results="response.data.data.results"></ResultsView>
            </div>
            <template v-if="debug">
              <pre style="flex:0 0 200px">{{Object.keys(response)}}</pre>
              <pre>{{response}}</pre>
            </template>
          </div>
        </div>

      </template>
    </div>
  </template>

  <script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import ResultsView from '@/components/ResultsView.vue'
//import * as md5 from 'md5.js'
import MD5 from "crypto-js/md5";
const axios = require('axios')
export default {
  name: 'Home',
  components: {
    //HelloWorld
    ResultsView
  },
  data:()=>{return {
    /*It is possible to reorder items*/
    typesFilter: {
      characters:{startsWith:'name',orderBy:['','name','-name','modified','-modified']},
      creators:{startsWith:'name',orderBy:['','lastName','-lastName','firstName','-firstName','middleName','-middleName','suffix','-suffix','modified','-modified']},
      events:{startsWith:'name',orderBy:['','name','-name','startDate','-startDate','modified','-modified']},
      series:{startsWith:'title',orderBy:['','title','-title','modified','-modified','startYear','-startYear']},
      comics:{startsWith:'title',orderBy:['','focDate','-focDate','onsaleDate','-onsaleDate','title','-title','issueNumber','-issueNumber','modified','-modified']},
      stories:{startsWith:'',orderBy:['','id','-id','modified','-modified']},
    },
    marvel_endpoint:"https://gateway.marvel.com:443/v1/public/",
    loading:false,
    search:'',
    //page:1,
  }},
  methods:{
    set_current_endpoint(value){
      if(this.current_endpoint==value){
        this.query(this.api_url)
        return
      }
      this.$store.commit({
        type: "set_query_response",
        response:null,
      })
      this.$store.commit({
        type: "set_current_endpoint",
        endpoint:value,
      })
      this.$store.commit({
        type: "set_last_endpoint",
        endpoint:value,
      })
    },
    query(url,page=null){
      if(this.debug)console.log('query',url,page)
        this.loading=true
      this.$store.commit({
        type: "set_last_queried_url",
        url:url,
      })
      if(this.response && this.response.data && this.response.data.data && this.response.data.data.limit)
        if(page){
          let limit=(this.response && this.response.data && this.response.data.data && this.response.data.data.limit)?this.response.data.data.limit:20
          url+=(url.match(/\?/)?'&':'?')+'offset='+(page-1)*limit
        }
        //console.log('query',url)
        if(!url || url=='')return
          let that=this
        switch (this.$store.state.query_method){
          case 'server proxy':
          axios.get(this.$store.state.server_url+'request.php?url='+encodeURIComponent(url),{withCredentials: true}).then(function (response) {
          // handle success
          if(that.debug)console.log(response);
          that.$store.commit({
            type: "set_query_response",
            response:response,
          })
        }).catch(function (error) {
          // handle error
          console.log(error);
        }).then(function () {
          that.loading=false
          // always executed
          //console.log('done')
        });
        break;
        case 'custom api keys':
        var $ts=Date.now()
        console.log('ts',$ts)
        var hash=this.stringToHash($ts+this.private_key+this.public_key)
        console.log('hash',hash,$ts+this.private_key+this.public_key)
        url +=(url.includes('?')?'&':'?')+ 'apikey='+this.public_key+'&ts='+$ts+'&hash='+hash
        console.log(url)
        axios.get(url,{withCredentials: false}).then(function (response) {
          // handle success
          if(that.debug)console.log(response);
          that.$store.commit({
            type: "set_query_response",
            response:response,
          })
        }).catch(function (error) {
          // handle error
          console.log(error);
        }).then(function () {
          that.loading=false
          // always executed
          //console.log('done')
        });
        break;
        default:
        console.log('default');
      }
    },
    _search(search){
      this.query(this.api_url+'?'+this.typesFilter[this.current_endpoint].startsWith+'StartsWith='+search)
    },
    stringToHash(string) {
      return MD5(string).toString()
      /*let hash = 0,i,char;
      if (string.length == 0) return hash;
      for (i = 0; i < string.length; i++) {
        char = string.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
      }
      return hash;*/
    },
  },
  watch:{
    api_url(new_value){
      console.log('api_url',new_value,this.$store.state.query_method)
      if(new_value!=''){
        this.query(new_value)
      }
    }
  },
  computed:{
   public_key() {
    return this.$store.state.public_key;
  },
  private_key() {
    return this.$store.state.private_key;
  },
  debug() {
    return this.$store.state.debug;
  },
  last_queried_url(){
    return this.$store.state.last_queried_url
  },
  endpoints(){
    return Object.keys(this.typesFilter)
  },
  current_endpoint(){
    return this.$store.state.current_endpoint
  },
  api_url(){
    if(!this.$store.state.current_endpoint || this.$store.state.current_endpoint=='')return ''
      return this.marvel_endpoint+this.$store.state.current_endpoint
  },
  response(){
    return this.$store.state.query_response
  },
  page(){
    return this.response.data.data.offset/this.response.data.data.limit+1
      //return this.$store.state.page
    },
    numPages() {
      //return 0
      let pages=[]
      let numberOfPages = Math.ceil(this.response.data.data.total/this.response.data.data.limit)
      //var length = Math.log(numberOfPages) * Math.LOG10E + 1 | 0
      for (let index = 1; index <= numberOfPages; index++) {
        if(index==1||index==numberOfPages||(index>this.page-10 && index<this.page+10) || (index/*+this.page*/)%parseInt(numberOfPages/(10))==0)
          pages.push(index)
      }
      return pages
      //return Math.ceil(this.response.data.data.total/this.response.data.data.limit);
    },
    last_endpoint(){
      return this.$store.state.last_endpoint;
    },
  },
  mounted(){
    //console.log(md5)
  }

}
</script>
<style scoped>
.debug{display:flex;}
.debug>*{flex:1;overflow:auto;}
.pagination{display:flex;flex-wrap:wrap;align-items: center;justify-content: center;list-style:none;padding:1em;}
.pagination .page-items{
  text-align: center;
}
button.page-link{
  display: inline-block;
  font-size: 20px;
  font-weight: 500;
  margin:0;
  padding:.2em;
  border-radius:0rem;
  border-width:2px 1px 2px 1px;
  border-style: solid;
  border-color: #000;
}

button.page-link:before{
  width:200%;
  left:-50%;
}
button.page-link:first-of-type{
  border-left-width:2px;
  border-radius:.5rem 0 0 .5rem;
}
button.page-link:last-of-type{
  border-right-width:2px;
  border-radius:0 .5rem .5rem 0;
}
.page-link:not(.current){cursor:pointer;}
.page-link.current:before{background-color:#555;}
nav{display:block;}
.uppercase{text-transform:uppercase;}
button svg{width:11px;vertical-align: middle;}
/*LOADER*/
.lds-ellipsis {
  display: block;
  position: relative;
  width: 80px;
  height: 80px;
  margin:0 auto;
}
.lds-ellipsis div {
  position: absolute;
  top: 33px;
  width: 13px;
  height: 13px;
  border-radius: 50%;
  background: #29b3ed;
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
  left: 8px;
  animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
  left: 8px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
  left: 32px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
  left: 56px;
  animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes lds-ellipsis3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes lds-ellipsis2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(24px, 0);
  }
}
.nav{
  display:flex;flex-wrap:wrap;
  justify-content: center;
}
@media (max-width:800px){
  .endpoints .button{
    padding:1em;
    margin:.5em;
  }
}


</style>

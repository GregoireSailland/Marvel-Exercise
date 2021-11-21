<template>
  <div class="settings">
    <h1>Settings</h1>
    <div class="flex-v">

      <h2>Query method: </h2>
      <div class="option-group">
        <label><input type="radio" :value="query_method" @click="set_query_method('server proxy')" :checked="query_method=='server proxy'">Server proxy</label>
        <div class="info">
          <p>Will use nextweb.ch server proxy to send requests</p>
          <p>This option is best suited for local dev environment</p>
        </div>
      </div>
      <div class="option-group">
        <label><input type="radio" :value="query_method" @click="set_query_method('custom api keys')" :checked="query_method=='custom api keys'">Custom api keys</label>
        <div class="flex-v left m-y">
          <label>public_key: 
            <input type="text" :value="public_key" @change="set_key('public_key',$event)">
          </label>
          <div v-if="env_public_key!='' && public_key!=env_public_key" class="default">Default: {{env_public_key}}</div>
          <label>private_key: 
            <input type="text" :value="private_key" @change="set_key('private_key',$event)">
          </label>
          <div v-if="env_private_key!='' && private_key!=env_private_key" class="default">Default: {{env_private_key}}</div>
        </div>
        <div class="info">
          <p>This app needs to be hosted on a sever</p>
          <p>This sever needs to be in the authorized referrers of your marvel api keys</p>
          <p>Or set widcard (*) to your <a href="https://developer.marvel.com/account" target="_blank">marvel api settings</a></p>
        </div>
      </div>
      <h2>Other: </h2>
      <div class="option-group">
        <label><input type="checkbox" :value="debug" @change="set_debug($event.target.checked)" :checked="debug">Show debug info (front-end + console)</label>
      </div>
    </div>
  </div>
</template>
<script>
export default{
  data(){
    return{

    }
  },
  methods: {
    set_key(key,$event){
      if(this.debug)console.log('set_key',key,$event.target.value)
        this.$store.commit({
          type: "set_key",
          key:key,
          value:$event.target.value
        })
    },
    set_query_method(query_method){
      this.$store.commit({
        type: "set_query_method",
        method:query_method,
      })
    },
    set_debug(value){
      this.$store.commit({
        type: "set_debug",
        value:value,
      })
    }
  },
  computed: {
    public_key() {
      return this.$store.state.public_key;
    },
    private_key() {
      return this.$store.state.private_key;
    },
    env_public_key() {
      return this.$store.state.marvel_public_key||'';
    },
    env_private_key() {
      return this.$store.state.marvel_private_key||'';
    },

    query_method() {
      return this.$store.state.query_method;
    },
    debug() {
      return this.$store.state.debug;
    }
  }
}
</script>
<style scoped>
.flex-v{
  display:flex;
  flex-direction:column;
  width: fit-content;
  margin-left:auto;
  margin-right:auto;
}
.flex-v.left{
  margin-left:0;
  margin-right: 0px;
  width: 100%;

}
.flex-v>*{
  display:flex;justify-content:space-between;
}
label>input[type="text"]{
  margin-left:1em;
  flex: 1;
}
.option-group{
  padding:1.5em;
  border:1px solid #333;
  border-radius:5px;
  display:flex;
  flex-direction:column;
  align-items: flex-start;
  margin-bottom:1em;
}
.m-y{margin-top:1em;margin-bottom:1em;}
.default{
  color:#999;
}
label{width:100%;}
</style>
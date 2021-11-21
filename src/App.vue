<template>
  <header class="top-bar">
    <h2 class="app-title">Marvel api</h2>
    <div class="spacer"></div>
    <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link> |
      <router-link to="/settings">Settings</router-link>
    </div>
  </header>
  <!--div class="test">
    <button @click="increment">increment</button>
    <div v-html="count"></div>
  </div-->
  <router-view/>
  
</template>
<script>
export default{
  methods: {
    increment() {
      this.$store.commit({
        type: "increment",
        amount: 5
      });
    }
  },
  computed: {
    count() {
      return this.$store.state.count;
    }
  },

}
</script>
<style>
/*RESET*/
html, body{
  margin: 0;
  padding: 0;
}
body{margin-bottom:30px;}
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
  border: 0;
  vertical-align: baseline;
}
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
  display: block;
}
body {
  line-height: 1;
}
ol, ul {
  list-style: none;
}
blockquote, q {
  quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
  content: '';
  content: none;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}
*{
  box-sizing:border-box;
}
/*APP*/
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
header.top-bar{
  z-index:999;
  position:sticky;
  top:0px;
  padding: 15px;
  background-color:#fff;
  box-shadow: 0 2px 2px #000;
}
header.top-bar:after{
  content:'';
  width:100%;
  display:table;
  clear:both;
}
.app-title{float:left;padding: 15px;margin:0 15px;}
#nav{
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  align-items: center;
}

#nav a {
  padding:15px;
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
h1{
  text-align:center;
}
.button{
  cursor:pointer;
  border: none;
  padding: 1.5rem 3rem;
  margin: 1em;
  position: relative;
  display: inline-block;
  text-transform: uppercase;
  letter-spacing: 0.05rem;
  font-weight: 700;
  font-size: 0.85rem;
  border-radius: 0.5rem;
  overflow: hidden;
  color: #fff;
  background: #000;
  z-index:1;
}
.button:before{
  position: absolute;
  z-index:-1;
  top: 0;
  height: 100%;
  content: '';
  background: #e62429;
  width: 120%;
  left: -10%;
  transform: skew(
  30deg);
  transition: transform 0.4s cubic-bezier(0.3, 1, 0.8, 1);
}
.button:hover::before {
  transform: translate3d(-100%,0,0);
}
.centered{
  display:flex;flex-direction:column;
  align-items:center;
  justify-content:center;
}
.padded{
  padding:0 2em;
}



@media (min-width:666px){
  .spacer{float:right;padding: 15px;margin:0 15px;width:147.4px;}
}
</style>

import { createStore } from 'vuex'
import VuexPersistence from 'vuex-persist'
const vuexLocal = new VuexPersistence({
	storage: window.localStorage
})
const store = createStore({
	state: { 
		count: 0,

		server_name:process.env.VUE_APP_SERVER_NAME,
		server_url:process.env.VUE_APP_SERVER_URL,
		marvel_public_key:process.env.VUE_APP_MARVEL_PUBLIC_KEY,
		marvel_private_key:process.env.VUE_APP_MARVEL_PRIVATE_KEY,


		public_key:process.env.VUE_APP_MARVEL_PUBLIC_KEY,
		private_key:process.env.VUE_APP_MARVEL_PRIVATE_KEY,

		query_method:'server proxy',
		current_endpoint:'',
		last_endpoint:'',
		query_response:null,
		last_queried_url:null,
		debug:false,

		//page:1,
	},
	mutations: { 
		increment(state, { amount }) {
			state.count += amount;
		},
		set_key(state,{key,value}){
			console.log(key,value)
			state[key]=value
		},
		set_query_method(state, { method }) {
			console.log('set_query_method')
			state.query_method = method;
		},
		set_current_endpoint(state, { endpoint }){
			console.log('set_current_endpoint')
			state.current_endpoint = endpoint;
		},
		set_query_response(state, { response }){
			console.log('set_query_response')
			state.query_response = response;
		},
		set_last_queried_url(state, { url }){
			console.log('set_last_queried_url')
			state.last_queried_url = url
		},
		set_debug(state, { value }){
			state.debug = value
		},
		set_last_endpoint(state, { endpoint }){
			state.last_endpoint = endpoint
		}
	},
	actions: {  },
	modules: {
	},
	plugins: [vuexLocal.plugin]
});
export default store
// store.js
import { reactive } from 'vue'

export const store = reactive({
  orders: [],
  params: {			
			perPage: 10,
			state:'All',
			search:'',
			order:'desc'
	},
})
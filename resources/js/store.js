import { createStore } from 'vuex'
import { getField, updateField } from 'vuex-map-fields'

// for shorthand mutation
const sets = property => (state, payload) => (state[property] = payload)

export default createStore({
    state: {
        goods: [],
        names: [],
        odata:[],
        currentCourse_US_RU: 61.75,
        beforeCourse_US_RU: 61.75,
        intervalId:null,
        intervalTime:15000,
        // intervalTime:1500,
        card: [],
        load: true,
    },
    mutations: {
        updateField,
        setOldData: sets('odata'),
        setGoods: sets('goods'),
        setNames: sets('names'),
        setCourse: sets('currentCourse_US_RU'),
        setLoader: sets('load'),
    },
    actions: {
        async saveOldData({ commit, getters }) {
            if (getters.data.length > 0) {
                commit('setOldData', getters.data)
            }
        },
        async loadData({ commit }) {
            commit('setLoader', true)
            // get data from back ( ?random=1 for randomize prices )
            const { data } = await axios.get(`/p2/read-data?random=1`)
            commit('setGoods', data.goods.Value.Goods)
            commit('setNames', data.names)
            commit('setLoader', false)
        },

        //Currency course emulate
        async getCourse({ commit, state }) {
            state.beforeCourse_US_RU = state.currentCourse_US_RU
            let randomCourse = parseFloat((Math.random() * (80 - 40) + 40).toFixed(2))
            commit('setCourse', randomCourse)
        },

        // time periods for get Data and Course
        startInterval({dispatch, state}){
            if (state.intervalId) {
                dispatch('destroyInterval')
            }
            state.intervalId = setInterval(()=>{
                dispatch('getCourse');
                dispatch('loadData');
            },state.intervalTime)
        },
        destroyInterval({state}){
            clearInterval(state.intervalId)
            delete state.intervalId
        },
    },
    getters: {
        getField,

        // Primary data values
        data: (state) => {
            let items = []
            Object.keys(state.names)
                .map(groupKey => Object.keys(state.names[groupKey]['B'])
                    .map(itemID => {
                        let f = state.goods.find(it => it['T'] === +itemID)
                        if (f) {
                            let agr = {
                                groupID: f['G'],
                                itemID: f['T'],
                                itemName: state.names[groupKey]['B'][itemID]['N'],
                                priceUS: f['C'],
                                priceRU: (+f['C'] * state.currentCourse_US_RU).toFixed(2),
                                count: f['P'],
                            }
                            items.push(agr)
                        }
                    }),
                )
            return items
        },

        // for titles groups
        groups: (state) => {
            return Object.entries(state.names)
                .map((v) => ({ id: +v[0], name: v[1]['G'] }))
        },

        // sum costs all goods (in roubles)
        totalGoodsCost: (state, getters) => {
            if (getters.data.length < 1) return
            return getters.data
                .map(v => v.priceRU * v.count)
                .reduce((pre, cur) => (+pre + +cur).toFixed(2))
        },

        // sum costs all goods in card (in roubles)
        totalCardCost: (state, getters) => {
            if (state.card.length < 1 || getters.data.length < 1) return;
            return state.card
                .map(v => v.priceUS * state.currentCourse_US_RU * v.count)
                .reduce((pre, cur) => (+pre + +cur).toFixed(2))
        },
    },
})

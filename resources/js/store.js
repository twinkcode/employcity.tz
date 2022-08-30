import { createStore } from 'vuex'
import { getField, updateField } from 'vuex-map-fields'

const set = property => (state, payload) => (state[property] = payload) //f

export default createStore({
    state: {
        goods: [],
        names: [],
        data:[],
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
        setData: set('data'),
        setOldData: set('odata'),
        setGoods: set('goods'),
        setNames: set('names'),
        setCourse: set('currentCourse_US_RU'),
        setLoader: set('load'),
    },
    actions: {
        async loadData({ commit, state, dispatch }) {
            commit('setLoader', true)
            const { data } = await axios.get(`/p2/read-data?random=1`)
            commit('setGoods', data.goods.Value.Goods)
            commit('setNames', data.names)
            dispatch('fillData')
        },
        // make one array with all fields
        fillData({commit, state}){ // may be iterate goods, is better for perfomance?..
            let oneFromTwoData = []
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
                            oneFromTwoData.push(agr)
                        }
                    }),
                )
            commit('setOldData', state.data)
            commit('setData', oneFromTwoData)
            commit('setLoader', false)

        },

        //Currency course emulate
        async getCourse({ commit, state }) {
            state.beforeCourse_US_RU = state.currentCourse_US_RU
            let randomCourse = parseFloat((Math.random() * (80 - 40) + 40).toFixed(2))
            commit('setCourse', randomCourse)
        },

        // periods for get Data and Course
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

        // sum costs all goods (in roubles)
        totalGoodsCost: (state, getters) => {
            if (state.data.length < 1) return
            return state.data
                .map(v => v.priceRU * v.count)
                .reduce((pre, cur) => (+pre + +cur).toFixed(2))
        },

        // sum costs all goods in card (in roubles)
        totalCardCost: (state, getters) => {
            if (state.card.length < 1 || state.data.length < 1) return;
            return state.card
                .map(v => v.priceUS * state.currentCourse_US_RU * v.count)
                .reduce((pre, cur) => (+pre + +cur).toFixed(2))
        },

        // for titles groups
        groups: (state) => {
            return Object.entries(state.names)
                .map((v) => ({ id: +v[0], name: v[1]['G'] }))
        },
    },
})

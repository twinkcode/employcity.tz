import { createStore } from 'vuex'
import { getField, updateField } from 'vuex-map-fields'

const set = property => (state, payload) => (state[property] = payload)

export default createStore({
    state: {
        goods: [],
        names: [],
        currentCourse_US_RU: 61.75,
        beforeCourse_US_RU: 60.75,
        intervalId:null,
        // intervalTime:15000,
        intervalTime:1500,
        card: [],
    },
    mutations: {
        updateField,
        setGoods: set('goods'),
        setNames: set('names'),
        setCourse: set('currentCourse_US_RU'),
    },
    actions: {
        async loadData({ commit}) {
            const { data } = await axios.get(`/p2/read-data`)
            commit('setGoods', data.goods.Value.Goods)
            commit('setNames', data.names)
        },
        async getCourse({ commit, state }) {
            state.beforeCourse_US_RU = state.currentCourse_US_RU
            let randomCourse = parseFloat((Math.random() * (80 - 40) + 40).toFixed(2))
            commit('setCourse', randomCourse)
        },
        destroyInterval({state}){
            clearInterval(state.intervalId)
        },
        startInterval({dispatch, state}){
            if (state.intervalId) {
                dispatch('destroyInterval')
                delete state.intervalId
            }
            state.intervalId = setInterval(()=>{
                dispatch('getCourse');
                dispatch('loadData');
            },state.intervalTime)
        }
    },
    getters: {
        getField,

        // sum costs all goods, in roubles
        totalGoodsCost: (state, getters) => {
            if (getters.mergedByNames.length < 1) return
            return getters.mergedByNames
                .map(v => v.priceRU * v.itemCount)
                .reduce((pre, cur) => (+pre + +cur).toFixed(2))
        },
        totalCardCost: (state, getters) => {
            if (state.card.length < 1 || getters.mergedByNames.length < 1) return;
            return state.card
                .map(v => v.priceUS * state.currentCourse_US_RU * v.count)
                .reduce((pre, cur) => (+pre + +cur).toFixed(2))
        },

        // for titles groups
        groups: (state) => {
            return Object.entries(state.names)
                .map((v, idx, arr) => ({ id: +v[0], name: v[1]['G'] }))
        },

        // make one array with all filds
        mergedByNames: (state) => { // may be iterate goods better for perfomance?..
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
                                itemCount: f['P'],
                            }
                            items.push(agr)
                        }
                    }),
                )
            return items
        },
    },
})

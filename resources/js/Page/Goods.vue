<template>
    <div class="card"
         v-for="(group,gKey) in groups"
         :key="gKey"
    >
        <div
            class="card-header h5 fw-bold text-bg-secondary "
            v-if="data.map(v => v.groupID).includes(group.id)"
        >
            {{ group.name }}
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">

                <template v-for="(item, itemKey) in data">
                    <li
                        v-if="group.id === item.groupID && odata[itemKey]"
                        :class="`${item.priceRU > odata[itemKey].priceRU ? 'bg-danger' : 'bg-success'}  bg-opacity-25 list-group-item d-flex justify-content-between align-items-center px-3`"
                        :key="itemKey"
                    >
                        <div class="item-name col-8 col-lg-10">
                            {{ item.itemName }} <span class="badge bg-secondary">{{item.count}}</span>
                        </div>
                        <div class="item-price col-4 col-lg-2 d-flex justify-content-end align-items-center">
                            <div>
                                <b :class="`${item.priceRU > odata[itemKey].priceRU ? 'text-danger' : 'text-success'}`">
                                    {{ item.priceRU }}
                                </b>
                                <span class="text-muted">₽</span>
                            </div>
                            <div class="arrows ms-2 text-muted d-flex flex-column justify-content-center align-items-center">
                                <div
                                    class="up"
                                    @click="itemPlus(item)"
                                />
                                <template v-if="card.find(v=>v.itemID === item.itemID)">
                                    <input class="currentCount" v-model="card.find(v=>v.itemID === item.itemID).count">
                                </template>
                                <template v-else>
                                    <input class="currentCount" />
                                </template>
                                <div
                                    class="down"
                                    @click="itemMinus(item)"
                                />
                            </div>
                        </div>
                    </li>
                </template>

            </ul>
        </div>

    </div>
    <div class="card-footer" style="height: 100px;">
        <div class="text-end">
            <span>Всего товаров на сумму: {{ totalGoodsCost }}</span>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { mapFields } from 'vuex-map-fields'

export default {
    name: 'Goods',
    computed: {
        ...mapState([ 'goods', 'names', 'odata', 'card', 'currentCourse_US_RU', 'beforeCourse_US_RU' ]),
        ...mapGetters([ 'groups', 'totalGoodsCost', 'data']),
        ...mapFields([ 'card' ]),
    },
    methods: {
        itemPlus(item) {
            let cardItem =  this.card.find(v=>v.itemID === item.itemID)
            if (!cardItem)  this.card.push({ itemID: item.itemID, count: 1, priceUS: item.priceUS})
            else if (this.data.find(v => v.itemID === cardItem.itemID).count > cardItem.count) cardItem.count++
        },
        itemMinus(item) {
            let cardItem =  this.card.find(v=>v.itemID === item.itemID)
            if (cardItem)
                cardItem.count <= 1
                ? this.card.splice((this.card.findIndex(v=>v.itemID === item.itemID)),1)
                : cardItem.count--
        },
    },
}
</script>

<style scoped lang="scss">

.arrows {
    margin-top: 16px;

    .up, .down {
        cursor: crosshair;
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .up {
        margin-top: -18px;
        background: url("/img/arrow_up.svg") no-repeat top center;
        height: 7.5px;
    }

    .down {
        background: url("/img/arrow_down.svg") no-repeat bottom center;
        height: 7.5px;
    }

    input.currentCount {
        width: 30px;
        height: 22px;
        font-size: 12px;
        text-align: center;
        border-color: lightgray;
        padding: 0;
        margin: 0;
    }
}
</style>

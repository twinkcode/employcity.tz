<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Корзина</h2>
            </div>
        </div>
    </div>
    <div class="d-flex" style="height: 70px;">
        <div class="vr"></div>
    </div>
    <div class="card mt-5">
        <div class="card-header h5 fw-bold text-bg-secondary">
            <div class="row row-cols-auto">
                <div class="col-6"><h6>Наименование</h6></div>
                <div class="col"><h6>Количество</h6></div>
                <div class="col"><h6>Цена</h6></div>
                <div class="col">&nbsp;</div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="card.length === 0" class="list-group-item">Нет товаров в корзине</div>
            <div
                v-else
                v-for="(item, itemKey) in card" :key="itemKey"
                :class="`row row-cols-auto justify-content-between my-2 ${itemKey < card.length - 1 ? 'border-bottom': '' }`"
            >
                <div class="col-7 w-50">{{ mItem(item).itemName }}</div>
                <div class="col w50div3 text-center">{{ item.count }}</div>
                <div class="col w50div3 text-center"><b
                    :class="`${mItem(item).priceRU > mItem(item).priceUS * beforeCourse_US_RU ? 'text-danger' : 'text-success'}`">{{ (mItem(item).priceRU * item.count).toFixed(2) }} </b>
                    <span class="text-muted">₽</span></div>
                <div class="col w50div3 text-center">
                    <button class="btn btn-sm btn-danger" @click="removeCardItem(item)">удалить</button>
                </div>
            </div>
        </div>

    </div>
    <div class="card-footer text-end" v-if="card.length > 0">
        ИТОГО: <span class="fw-bold">{{totalCardCost.toFixed(2)}}<span class="text-muted"> ₽</span></span>
    </div>
    <div class="col-12 d-flex justify-content-around mt-5">
        <button class="btn btn-warning" @click="startInterval">startInterval</button>
        <input class="form-control w-25" v-model="intervalTime">
        <button class="btn btn-warning ms-3" @click="destroyInterval">destroyInterval</button>
    </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import { mapFields } from 'vuex-map-fields'

export default {
    name: 'Card',
    computed: {
        ...mapState([ 'goods', 'names', 'card', 'beforeCourse_US_RU' ]),
        ...mapGetters([ 'totalGoodsCost', 'totalCardCost', 'mergedByNames', 'groups' ]),
        ...mapFields([ 'card','intervalTime' ]),
    },
    methods: {
        ...mapActions([ 'destroyInterval', 'startInterval' ]),
        removeCardItem(item) {
            this.card.splice((this.card.findIndex(v => v.itemID === item.itemID)), 1)
        },
        mItem(item) {
            return this.mergedByNames.find(v => v.itemID === item.itemID)
        },
    },
}
</script>

<style scoped>
.w50div3 {
    width: calc(50% / 3);
}
</style>

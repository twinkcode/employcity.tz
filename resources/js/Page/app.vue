<template>
    <main>
        <Loader />
        <div class="container">
            <div class="row mt-2">
                <div class="col-12 col-lg-6">
                    <h1>Товары</h1>
                    <SliderCourse />
                    <div class="my-2"></div>
                    <Goods />
                </div>
                <div class="col-12 col-lg-6">
                    <Card />
                </div>
            </div>
        </div>
    </main>

    <footer style="min-height: 100px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">CodeMaster</div>
                <div class="col-12 text-center">
                    <a href="https://t.me/codemastercode">telegram</a>
                    <span> © </span>
                    <a href="mailto:twinkcode@gmail.com">E-mail</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
import Card from './Card.vue'
import Goods from './Goods.vue'
import SliderCourse from '../components/SliderCourse.vue'
import Loader from '../components/Loader.vue'
import { mapState, mapMutations } from 'vuex'

export default {
    components: { Card, Goods, SliderCourse, Loader },
    name: 'app',
    computed: {
        ...mapState([ 'goods', 'load', 'names', 'data', 'card', 'beforeCourse_US_RU' ]),
        ...mapMutations(['setLoader'])
    },
    async created()  {
        this.$store.commit('setLoader', true)
        await this.$store.dispatch('loadData');
        await this.$store.dispatch('fillData');
        await this.$store.dispatch('startInterval');
        this.$store.commit('setLoader', false)
    },
}
</script>


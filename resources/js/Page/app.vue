<template>
    <main>
        <Loader />
        <div class="container">
            <div class="row mt-2">
                <div class="col-12">
                    <a class="navbar-brand align-items-center" href="/">
                        <span class="px-3 fw-semibold text-primary text-opacity-75">
                        employ<span class="text-warning">•</span>city
                    </span>
                        <span class="position-relative btn btn-outline-info">1 часть ТЗ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">blade</span></span>
                    </a>
                </div>
            </div>
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

    },
    async mounted(){
        await this.$store.dispatch('loadData');
        await this.$store.dispatch('saveOldData');
        await this.$store.dispatch('startInterval');
        this.$store.commit('setLoader', false)
    }
}
</script>


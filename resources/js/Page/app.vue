<template>
    <main>
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
</template>

<script>
import Card from './Card.vue'
import Goods from './Goods.vue'
import SliderCourse from '../components/SliderCourse.vue'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Card, Goods, SliderCourse },
    name: 'app',
    created()  {
        this.$store.dispatch('loadData');
        this.$store.dispatch('startInterval');
    },
}
</script>
<style scoped>

</style>

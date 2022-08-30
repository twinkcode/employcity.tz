<template>
    <div class="d-flex flex-column align-items-center justify-content-between">
        <div
            class="d-flex flex-column justify-content-center mb-4"
            style="max-width: 320px ;"
        >
            <label for="inputCurrency" class="form-label text-muted">Курс доллара к рублю</label>
            <input
                id="inputCurrency"
                class="form-control"
                v-model="currentCourse_US_RU"
            />
        </div>
        <div style="width: 100%">
            <Slider
                :format="format"
                :options="options"
                :min="40.00"
                :max="80.00"
                v-model="currentCourse_US_RU"
            />
        </div>

    </div>
</template>

<script>
import Slider from '@vueform/slider'
import { mapFields } from 'vuex-map-fields'

export default {
    name: 'SliderCourse',
    components: { Slider },
    data() {
        return {
            options:{
                step: 0.01
            },
            format:
                {
                    prefix: '$',
                    decimals: 2,
                },

        }
    },
    computed: {
        ...mapFields([ 'currentCourse_US_RU' ]),
    },
}
</script>
<style src="@vueform/slider/themes/default.css"></style>

<!--<style scoped>-->

<!--</style>-->

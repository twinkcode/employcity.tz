<template>
    <div :class="`load_wrap ${!load ? 'd-none' : '' }`" id="loader">
        <div class="loader" style="--b: 20px;--c:#000;width:80px;--n:15;--g:7deg"></div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'

export default {
    name: 'Loader',
    computed: {
        ...mapState([ 'load' ]),
        // ...mapFields([ 'load' ]),
    }
}
</script>

<style scoped>
.loader {
    --b: 10px; /* border thickness */
    --n: 10; /* number of dashes*/
    --g: 10deg; /* gap  between dashes*/
    --c: red; /* the color */

    width: 100px; /* size */
    aspect-ratio: 1;
    border-radius: 50%;
    padding: 1px; /* get rid of bad outlines */
    background: conic-gradient(#0000, var(--c)) content-box;
    --_m: /* we use +/-1deg between colors to avoid jagged edges */ repeating-conic-gradient(#0000 0deg,
    #000 1deg calc(360deg / var(--n) - var(--g) - 1deg),
    #0000 calc(360deg / var(--n) - var(--g)) calc(360deg / var(--n))),
    radial-gradient(farthest-side, #0000 calc(98% - var(--b)), #000 calc(100% - var(--b)));
    -webkit-mask: var(--_m);
    mask: var(--_m);
    -webkit-mask-composite: destination-in;
    mask-composite: intersect;
    animation: load 1s infinite steps(var(--n));
}

@keyframes load {
    to {
        transform: rotate(1turn)
    }
}

.load_wrap {
    position: fixed;
    margin: 0;
    width: 100vw;
    height: 100vh;
    display: grid;
    place-content: center;
    align-items: center;
    grid-auto-flow: column;
    gap: 20px;
    background: rgba(255, 235, 239, 0.33);
    z-index: 1;
}
</style>

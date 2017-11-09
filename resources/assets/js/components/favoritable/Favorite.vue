<template>
    <button :class="classes" :disabled="!signedIn" @click="toggle">
        <span class="glyphicon glyphicon-star"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
export default {
    props: ['type', 'model'],

    data() {
        return {
            count: this.model.favoritesCount,
            active: this.model.isFavorited
        };
    },

    computed: {
        classes() {
            return ['btn', 'btn-xs',  this.active ? 'btn-primary' : 'btn-default'];
        },

        endpoint() {
            return `/favorites/${this.type}/${this.model.id}`;
        }
    },

    methods: {
        toggle() {
            this.active ? this.destroy() : this.create();
        },

        create() {
            axios.post(this.endpoint);

            this.active = true;
            this.count++;
        },

        destroy() {
             axios.delete(this.endpoint);

            this.active = false;
            this.count--;
        }
    }
};
</script>

<style scoped>
    button { height: 20px; margin-right: 3px; }
</style>

<template>
    <div class="panel panel-primary sidebar-favorite">
        <a :href="href">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-star"></span>
                <strong v-text="title"></strong>
            </div>
        </a>
    </div>
</template>

<script>
export default {
    data() {
        return {
            active: false
        };
    },

    computed: {
        href() {
            return this.active ? '/posts' : '/posts?favorite=true';
        },

        title() {
            return this.active ? 'Show All' : 'Favorite';
        }
    },

    created() {
        this.active = location.search.includes('favorite');
    }
};
</script>

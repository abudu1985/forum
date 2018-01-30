<script>

    import Favorite from './Favorite.vue';

    export default {
        props: ['attributes'],

        components: { Favorite },

        data(){
            return {
                editing: false,
                body: this.attributes.body
            };
        },

        methods: {
            update(){
                axios.patch('/forum/replies/' + this.attributes.id, {
                    body: this.body
                });
                this.editing = false;

                flash('Updated!');
            },

            destroy(){
                axios.delete('/forum/replies/' + this.attributes.id);
                
                $(this.$el).fadeOut(300, () => {
                    flash('Yor reply has been deleted!');
                });
            }
        }
    }
</script>
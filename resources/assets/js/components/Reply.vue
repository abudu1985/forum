<script>
    export default {
        props: ['attributes'],
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
            }
        }
    }
</script>
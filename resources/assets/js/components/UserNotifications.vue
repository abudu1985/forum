<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </li>

</template>

<script>
    export default {
       data() {
           return { notifications: false }
       },
        created() {
            axios.get('/forum/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        },
        methods: {
           markAsRead(notification){
               axios.delete('/forum/profiles/' + window.App.user.name + '/notifications/' + notification.id)
           }
//            subscribe() {
//                axios[
//                    (this.active ? 'delete' : 'post')
//                    ](location.pathname + '/subscriptions');
//                this.active = ! this.active;
//            }
        }
    }
</script>
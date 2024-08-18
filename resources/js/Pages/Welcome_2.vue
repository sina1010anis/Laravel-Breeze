<template>
    <p>Page Welcome 2 (Hellow Inretia) <span>#-# {{ username }} #-#</span></p>
    <Link href="/vue-page" method="get" as="button" type="button">Go back</Link>

</template>

<script>
import {Link} from '@inertiajs/vue3';

export default {
    name: "Welcome_2",
    components:{
        Link,
    },
    props:{
        username: String
    }
    // computed:{
    //     users() {
    //         return page.props.auth.username
    //     },
    // }

}


</script>

<style scoped>

</style>

<template>
    <p>Hello</p>
    <Link href="/vue-page-2" method="get" as="button" type="button">Logout</Link>

</template>

<script>
import {Link} from '@inertiajs/vue3';

export default {
    name: "Welcome",
    components:{
        Link,
    }
}

</script>

<style scoped>

</style>

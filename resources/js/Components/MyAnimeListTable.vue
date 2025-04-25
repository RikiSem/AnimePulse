<template>
    <table v-if="$props.items !== null">
        <tr>
            <th class="name-col">Название</th><th class="status-col"></th><th class="status-col">Статус</th><th class="user-status-col">Статус пользователя</th><th class="user-rate-col">Оценка пользователя</th><th class="rate-col">Общая оценка</th>
        </tr>
        <tr v-for="item in items">
            <td class="name-col">
                <a :href="route('anime.show', {
                    'id': item.id
                })">{{ item.name }}</a>
            </td>
            <td class="fav-col">
                <svg v-if="item.current_user.user_favorite" class="svg-g-1" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="m14.121 19.337c-.467.453-.942.912-1.424 1.38-.194.189-.446.283-.697.283s-.503-.094-.697-.283c-4.958-4.813-9.303-8.815-9.303-12.54 0-3.348 2.582-5.177 5.234-5.177 1.831 0 3.636.867 4.766 2.563 1.125-1.688 2.935-2.554 4.771-2.554 2.649 0 5.229 1.815 5.229 5.168 0 .681-.144 1.37-.411 2.072-.375-.361-.798-.673-1.258-.925.113-.393.169-.773.169-1.147 0-2.52-1.933-3.668-3.729-3.668-1.969 0-3.204 1.355-4.159 2.694-.141.197-.369.314-.612.314-.243-.001-.471-.119-.611-.317-.953-1.347-2.165-2.699-4.155-2.7-.985 0-1.937.346-2.61.95-.735.658-1.124 1.602-1.124 2.727 0 2.738 3.046 5.842 8.5 11.127.346-.336.682-.663 1.007-.981.327.383.701.724 1.114 1.014zm3.38-9.335c2.58 0 4.499 2.107 4.499 4.499 0 2.58-2.105 4.499-4.499 4.499-2.586 0-4.5-2.112-4.5-4.499 0-2.406 1.934-4.499 4.5-4.499zm.5 3.999v-1.5c0-.265-.235-.5-.5-.5-.266 0-.5.235-.5.5v1.5h-1.5c-.266 0-.5.235-.5.5s.234.5.5.5h1.5v1.5c0 .265.234.5.5.5.265 0 .5-.235.5-.5v-1.5h1.499c.266 0 .5-.235.5-.5s-.234-.5-.5-.5z"/>
                </svg>
                <svg v-else class="svg-g" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="m14.121 19.337c-.467.453-.942.912-1.424 1.38-.194.189-.446.283-.697.283s-.503-.094-.697-.283c-4.958-4.813-9.303-8.815-9.303-12.54 0-3.348 2.582-5.177 5.234-5.177 1.831 0 3.636.867 4.766 2.563 1.125-1.688 2.935-2.554 4.771-2.554 2.649 0 5.229 1.815 5.229 5.168 0 .681-.144 1.37-.411 2.072-.375-.361-.798-.673-1.258-.925.113-.393.169-.773.169-1.147 0-2.52-1.933-3.668-3.729-3.668-1.969 0-3.204 1.355-4.159 2.694-.141.197-.369.314-.612.314-.243-.001-.471-.119-.611-.317-.953-1.347-2.165-2.699-4.155-2.7-.985 0-1.937.346-2.61.95-.735.658-1.124 1.602-1.124 2.727 0 2.738 3.046 5.842 8.5 11.127.346-.336.682-.663 1.007-.981.327.383.701.724 1.114 1.014zm3.38-9.335c2.58 0 4.499 2.107 4.499 4.499 0 2.58-2.105 4.499-4.499 4.499-2.586 0-4.5-2.112-4.5-4.499 0-2.406 1.934-4.499 4.5-4.499zm.5 3.999v-1.5c0-.265-.235-.5-.5-.5-.266 0-.5.235-.5.5v1.5h-1.5c-.266 0-.5.235-.5.5s.234.5.5.5h1.5v1.5c0 .265.234.5.5.5.265 0 .5-.235.5-.5v-1.5h1.499c.266 0 .5-.235.5-.5s-.234-.5-.5-.5z"/>
                </svg>
            </td>
            <td class="status-col">
                {{ item.status }}
            </td>
            <td class="user-status-col">
                <template v-if="item.statusForUser !== null">
                    {{ item.statusForUser.title }}
                </template>
                <template v-else>
                    Не добавлено
                </template>
            </td>
            <td class="user-rate-col">
                <span v-if="item.current_user.user_rate !== null">
                    {{ item.userRate }}
                </span>
                <span v-else>
                    -
                </span>
            </td>
            <td class="rate-col">
                {{ item.rate }}
            </td>
        </tr>
    </table>
    <div v-else>
        <table>
            <tr>
                <th class="name-col">Название</th><th class="status-col">Статус</th><th class="user-status-col">Статус пользователя</th><th class="user-rate-col">Оценка пользователя</th><th class="rate-col">Общая оценка</th>
            </tr>
        </table>
        <h1>Тут пока что ничего нет</h1>
    </div>
</template>

<script>
export default {
    name: "MyAnimeListTable",
    props:{
        items:{
            type: Object
        }
    }
}
</script>

<style scoped>
.name-col{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-align: left;
    max-width: 20vw;
}
.fav-col{

}
.svg-g-1{
    width: fit-content;
    margin: auto;
    fill: var(--default-green);
}
.svg-g{
    width: fit-content;
    margin: auto;
    fill: white;
}
td{
    padding: 7px;
}
table{
    text-align: center;
    width: 100%;
}
h1{
    padding: 10px 0px 0px 0px;
    text-align: center;
}
</style>

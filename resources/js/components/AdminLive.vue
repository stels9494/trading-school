<template>
    <div
        class="d-flex flex-wrap"
    >
        <div
            v-for="command in commands"
            class="card mr-2 mb-2"
        >
            <div class="card-body">
                <h5 class="card-title">{{ command.name }}</h5>
                <table class="w-100">
                    <tr>
                        <td>Баланс: </td>
                        <td><b>{{ command.balance }} ₽</b></td>
                    </tr>
                    <tr>
                        <td>В акциях: </td>
                        <td><b>{{ command.stocks_balance }} ₽</b></td>
                    </tr>
                    <tr>
                        <td>Акций в портфеле:</td>
                        <td><b>{{ command.stocks_count }}</b></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "AdminLive",
    props: {
        commands: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            history: {

            }
        }
    },
    created() {
        this.commands.forEach(command => {
            window.Echo.private(`command.${command.id}`)
                .listen('CommandBuySell',  ({data}) => {
                    let command = this.commands.find((el) => (el.id == data.command.id))
                    command.balance = data.command.balance
                    command.stocks_balance = data.command.stocks_balance
                    command.stocks_count = data.command.stocks_count

                    this.$forceUpdate();

                })
                .listen('UpdateCharts', ({data}) => {
                    let command = this.commands.find((el) => (el.id == data.command.id))
                    command.balance = data.command.balance
                    command.stocks_balance = data.command.stocks_balance
                    command.stocks_count = data.command.stocks_count

                    this.$forceUpdate();
                })
        })
    }
}
</script>
<style>
    .card {
        width: 300px;
    }
</style>

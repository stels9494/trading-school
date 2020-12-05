<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <div class="mr-2">
                    <b>{{ command.name }}</b>
                </div>
                <div class="mr-2">
                    Свободные деньги команды: <b>{{ command.balance }} $</b>
                </div>
                <div class="mr-2">
                    Текущая стоимость акций: <b>{{ portfel_current_value }} $</b>
                </div>
                <div class="mr-2">
                    Общий баланс: <b>{{ command.balance + portfel_current_value }} $</b>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <b-dropdown variant="link" :text="user.fio" class="navbar-nav m-2 ml-auto">
                        <b-dropdown-item-button>
                            <a href="/logout">Выход</a>
                        </b-dropdown-item-button>
                    </b-dropdown>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="container">
                <div
                    v-for="stock in stocks"
                    class="mb-4"
                >
                    <div>
                        <b>#{{ stock.id }} {{ stock.name }}</b>
                    </div>
                    <div
                        class="row box border rounded shadow p-3 align-items-start"
                    >
                        <div
                            class="col-lg-4 row"
                        >
                            <div
                                class="col-lg-4"
                            >
                                <b-form-group
                                    label="Цена:"
                                    :label-for="'prices-'+stock.id"
                                >
                                    <b-form-input
                                        :id="'prices-'+stock.id"
                                        v-model="prices[stock.id]"
                                        type="number"
                                        disabled
                                    ></b-form-input>
                                </b-form-group>
                            </div>
                            <div
                                class="col-lg-4"
                            >
                                <b-form-group
                                    label="В портфеле:"
                                    :label-for="'portfel-'+stock.id"
                                >
                                    <b-form-input
                                        :id="'portfel-'+stock.id"
                                        v-model="portfel[stock.id]"
                                        type="number"
                                        disabled
                                    ></b-form-input>
                                </b-form-group>
                            </div>
                            <div
                                class="col-lg-4"
                            >
                                <b-form-group
                                    label="Стоимость:"
                                    :label-for="'actual-portfel-'+stock.id"
                                >
                                    <b-form-input
                                        :id="'actual-portfel-'+stock.id"
                                        type="number"
                                        :value="prices[stock.id]*portfel[stock.id]"
                                        disabled
                                    ></b-form-input>
                                </b-form-group>
                            </div>

                            <div
                                class="col-lg-4"
                                v-if="user.roles[0].name == 'commander'"
                            >
                                <b-form-input
                                    :id="'buy-'+stock.id"
                                    v-model="buy[stock.id]"
                                    type="number"
                                    min="0"
                                ></b-form-input>
                            </div>
                            <div
                                class="col-lg-4"
                                v-if="user.roles[0].name == 'commander' && status"
                            >
                                <a
                                    class="btn btn-success"
                                    @click="buyStock(stock)"
                                >
                                    Купить
                                </a>
                            </div>
                            <div
                                class="col-lg-4"
                                v-if="user.roles[0].name == 'commander' && status"
                            >
                                <a
                                    class="btn btn-danger"
                                    @click="sellStock(stock)"
                                >
                                    Продать
                                </a>
                            </div>
                        </div>
                        <div
                            class="col-lg-6"
                        >
                            <div id="chart">
                                <div id="chart-timeline">
                                    <apexchart
                                        type="area"
                                        height="200"
                                        :ref="'chart_'+stock.id"
                                        :options="chartOptions"
                                        :series="series[stock.id]"
                                    ></apexchart>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-lg-2 trading_history"
                        >
                            <div
                                v-for="item in trading_history[stock.id]"
                            >
                                <span :class="'type-'+item.action">{{ item.time }}</span> <b>{{ item.count }} шт</b> {{ item.price }}$
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    export default {
        name: 'GameScreen',
        props: {
            stocks:{
                type: Array,
                default: []
            },
            user: {},
            command: {},
            status: {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                prices: {},
                portfel: {},
                buy: {},
                trading_history: {},
                chartOptions: {
                    chart: {
                        id: 'area-datetime',
                        type: 'area',
                        height: 350,
                        zoom: {
                            autoScaleYaxis: true
                        }
                    },
                    annotations: {
                    },
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        size: 0,
                        style: 'hollow',
                    },
                    colors: ['#7AC679'],
                    xaxis: {
                        type: 'datetime',
                        tickAmount: 6,
                    },
                    tooltip: {
                        x: {
                            format: 'dd MMM yyyy'
                        }
                    },
                    fill: {
                        color: '#7AC679',
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.9,
                            stops: [0, 100]
                        }
                    },
                },
                series: {},
            }
        },
        mounted() {

        },
        computed: {
            portfel_current_value: function(){
                let value = 0;
                this.stocks.forEach((el) => {
                    let dop = this.prices[el.id] * this.portfel[el.id]
                    if (dop > 0)
                        value += dop
                });
                return value;
            }
        },
        created() {
            this.stocks.forEach((el) => {
                this.prices[el.id] = 0
                this.getPrices(el);
            });

            window.Echo.private(`command.${this.command.id}`)
                .listen('StartGame', ({data}) => {
                    this.status = 1;
                    let text = 'График будет обновлять каждые '+data.month_in_minute+' мин.';
                    let title = `Игра началась!`;
                    let variant =  'success';

                    this.showToast(text, title, variant);
                })
                .listen('UpdateCharts', ({data}) => {

                    this.stocks.forEach((el) => {
                        this.prices[el.id] = 0
                        this.getPrices(el);
                    })

                    let text = 'Новые данные загружены!';
                    let title = `Игра`;
                    let variant =  'info';

                    this.showToast(text, title, variant);

                })
                .listen('StopGame',  ({data}) => {
                    this.status = 0;
                    let text = 'Спасибо!';
                    let title = `Игра остановлена!`;
                    let variant =  'danger';

                    this.showToast(text, title, variant);
                })
                .listen('CommandBuySell',  ({data}) => {

                    this.getPrices(data.stock);

                    this.command.balance = data.command.balance

                    if (data.type == 'buy') {
                        let text = 'Капитан купил "' + data.stock.name + '"';
                        let title = `Покупка`;
                        let variant =  'success';
                        this.showToast(text, title, variant);
                    } else {
                        let text = 'Капитан продал "' + data.stock.name + '"';
                        let title = `Продажа`;
                        let variant =  'danger';
                        this.showToast(text, title, variant);
                    }
                })
        },
        methods: {
            buyStock(item){
                axios.post('/commands/'+this.command.id+'/stocks/'+item.id+'/buy', {
                    count: this.buy[item.id]
                }).then((response) => {
                    this.buy[item.id] = 0
                    this.command.balance = response.data.command.balance
                    this.$set(this.portfel, item.id, response.data.portfel)
                    this.$set(this.trading_history, item.id, response.data.trading_history)
                    this.$forceUpdate();
                })
            },
            sellStock(item){
                axios.post('/commands/'+this.command.id+'/stocks/'+item.id+'/sell', {
                    count: this.buy[item.id]
                }).then((response) => {
                    this.buy[item.id] = 0
                    this.command.balance = response.data.command.balance
                    this.$set(this.portfel, item.id, response.data.portfel)
                    this.$set(this.trading_history, item.id, response.data.trading_history)
                    this.$forceUpdate();
                })
            },
            showToast(text, title, variant){
                this.$bvToast.toast(
                    text,
                    {
                        title: title,
                        variant: variant,
                        toaster: 'b-toaster-bottom-right',
                        solid: true,
                        appendToast: true
                    }
                )
            },
            getPrices(item){
                axios.get('/stocks/'+item.id, { params: {action: 'quotations'}})
                    .then((response) => {
                        this.$refs['chart_'+item.id][0].updateSeries([{
                            name: item.name,
                            data: response.data.history
                        }])
                        this.$set(this.prices, item.id, response.data.price)
                        this.$set(this.portfel, item.id, response.data.portfel)
                        this.$set(this.trading_history, item.id, response.data.trading_history)
                        this.$forceUpdate();
                    })
            }
        }
    }
</script>
<style>
    .type-BUY {
        color: #38c172;
    }
    .type-SELL {
        color: #e3342f;
    }
    .trading_history {
        overflow-y: scroll;
        max-height: 200px;
    }
</style>

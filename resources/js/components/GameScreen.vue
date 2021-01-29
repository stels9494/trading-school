<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <div class="mr-2">
                    <b>{{ command.name }}</b>
                </div>

                <div
                    class="mr-2"
                    :class="status ? 'text-success' : 'text-danger'"
                >
                    <b>{{ status ? (is_pause ? 'Пауза' : 'Игра запущена') : 'Игра не запущена' }}</b>
                </div>

                <div class="mr-2">
                    Свободные деньги: <b>{{ command.balance | balance }} ₽</b>
                </div>
                <div class="mr-2">
                    Акций в портфеле: <b>{{ command.stocks_count }} шт.</b>
                </div>
                <div class="mr-2">
                    Стоимость портфеля: <b>{{ command.stocks_balance | balance }} ₽</b>
                </div>
                <div class="mr-2">
                    Общий баланс: <b>{{ (command.balance + command.stocks_balance) | balance }} ₽</b>
                </div>
                <div class="mr-2">
                    Текущая дата: <b>{{ current_date }}</b>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <b-dropdown variant="link" :text="user.fio" class="navbar-nav m-2 ml-auto">
                        <a href="/logout">
                            <b-dropdown-item-button>
                                Выход
                            </b-dropdown-item-button>
                        </a>
                    </b-dropdown>
                </div>
            </div>
        </nav>
        <b-navbar
            v-if="status && !is_pause"
            toggleable="lg"  fixed="bottom" variant="light"
        >
            <div class="w-100">
                <b-progress class="mt-2" :max="month_in_minute*60" show-value>
                    <b-progress-bar :value="timer" :max="month_in_minute*60" variant="success"></b-progress-bar>
                </b-progress>
            </div>
        </b-navbar>
        <main class="pb-4 pt-5 mt-5">
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
                                        :value="(prices[stock.id]*portfel[stock.id]) | balance"
                                        disabled
                                    ></b-form-input>
                                </b-form-group>
                            </div>

                            <div
                                class="col-lg-4"
                                v-if="user.roles[0].name == 'commander' && status && !is_pause"
                            >
                                <b-form-input
                                    :id="'buy-'+stock.id"
                                    v-model="buy[stock.id]"
                                    type="number"
                                    min="0"
                                ></b-form-input>
                            </div>
                            <div
                                class="col-6 col-lg-4 text-center text-lg-left"
                                v-if="user.roles[0].name == 'commander' && status && !is_pause"
                            >
                                <a
                                    class="btn btn-success"
                                    @click="buyStock(stock)"
                                >
                                    Купить
                                </a>
                            </div>
                            <div
                                class="col-6 col-lg-4 text-center text-lg-left"
                                v-if="user.roles[0].name == 'commander' && status && !is_pause"
                            >
                                <a
                                    class="btn btn-danger"
                                    @click="sellStock(stock)"
                                >
                                    Продать
                                </a>
                            </div>
                            <div
                                class="col-lg-12 pt-3"

                            >
                                <span>Ср. цена покупок акций в портфеле: </span>
                                <span class="font-weight-bold">{{ (trading_history[stock.id] && trading_history[stock.id][0]) ? trading_history[stock.id][0].average_price : 0  }} ₽</span>
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
                                <span :class="'type-'+item.action">{{ item.time }}</span> <b>{{ item.count }} шт</b> {{ item.price }}₽
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
            stocks_props:{
                type: Array,
                default: []
            },
            user: {},
            command: {},
            status_prop: {
                type: Number,
                default: 0
            },
            is_pause_prop: {
                type: Number,
                default: 0
            },
            month_in_minute_prop: {
                type: Number,
                default: 1
            },
            current_date_prop: {
                type: String,
                default: ""
            }
            // stocks_balance_prop: 0,
            // stocks_count_prop: 0,
        },
        data() {
            return {
                stocks: this.stocks_props,
                prices: {},
                portfel: {},
                buy: {},
                status: this.status_prop,
                is_pause: this.is_pause_prop,
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
                portfel_current_value: 0,
                timer: 0,
                month_in_minute: this.month_in_minute_prop,
                current_date: this.current_date_prop,
                // stocks_balance: this.stocks_balance_prop,
                // stocks_count: this.stocks_count_prop,
            }
        },
        mounted() {
            let intervalId = setInterval(() => {
                if (this.status && !this.is_pause){
                    this.timer++;
                }
            }, 1000);
        },
        created() {
            this.stocks.forEach((el) => {
                this.prices[el.id] = 0
                this.getPrices(el);
            });

            window.Echo.private(`command.${this.command.id}`)
                .listen('UpdateStocksList', ({data}) =>{
                    this.stocks = data;
                    let text = 'Список акций обновлен.';
                    let title = `Внимание!`;
                    let variant =  'info';
                    this.stocks.forEach((el) => {
                        this.prices[el.id] = 0
                        this.getPrices(el);
                    });
                    this.showToast(text, title, variant);
                })
                .listen('StartGame', ({data}) => {

                    this.status = 1;
                    this.month_in_minute = data.month_in_minute;
                    this.timer = 0;
                    this.current_date = data.current_date;
                    let text = 'График будет обновляться каждые '+data.month_in_minute+' мин.';
                    let title = `Игра началась!`;
                    let variant =  'success';

                    this.stocks.forEach((el) => {
                        this.prices[el.id] = 0
                        this.getPrices(el);
                    })

                    this.showToast(text, title, variant);
                })
                .listen('UpdateCharts', ({data}) => {

                    this.stocks.forEach((el) => {
                        this.prices[el.id] = 0
                        this.getPrices(el);
                    })
                    this.current_date = data.current_date;
                    this.timer = 0;
                    let text = 'Новые данные загружены!';
                    let title = `Игра`;
                    let variant =  'info';

                    this.showToast(text, title, variant);

                })
                .listen('StopGame',  ({data}) => {
                    this.status = 0;
                    this.timer = 0;

                    this.stocks.forEach((el) => {
                        this.prices[el.id] = 0
                        this.getPrices(el);
                    })

                    let text = 'Спасибо!';
                    let title = `Игра остановлена!`;
                    let variant =  'danger';

                    this.showToast(text, title, variant);
                })
                .listen('CommandBuySell',  ({data}) => {

                    this.getPrices(data.stock);

                    this.command.balance = data.command.balance
                    this.command.stocks_balance = data.stocks_balance
                    this.command.stocks_count = data.stocks_count

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
                .listen('PauseGame', ({data}) => {
                    this.is_pause = data.is_pause
                    this.month_in_minute = data.month_in_minute
                    if (this.is_pause){
                        let text = 'Поставлена на паузу';
                        let title = `Игра`;
                        let variant =  'warning';
                        this.showToast(text, title, variant);
                    }else{
                        let text = 'Игра продолжается!';
                        let title = `Игра`;
                        let variant =  'warning';
                        this.showToast(text, title, variant);

                        text = 'График будет обновляться каждые '+data.month_in_minute+' мин.';
                        title = `Игра началась!`;
                        variant =  'success';
                        this.showToast(text, title, variant);
                    }
                })
        },
        methods: {
            portfel_calc(){
                let value = 0;
                this.stocks.forEach((el) => {
                    let dop = this.prices[el.id] * this.portfel[el.id]
                    if (dop > 0)
                        value += dop
                });

                this.portfel_current_value = value;
            },
            buyStock(item){
                axios.post('/commands/'+this.command.id+'/stocks/'+item.id+'/buy', {
                    count: this.buy[item.id]
                }).then((response) => {
                    this.buy[item.id] = 0
                    this.command.balance = response.data.command.balance
                    this.command.stocks_balance = response.data.command.stocks_balance
                    this.command.stocks_count = response.data.command.stocks_count

                    this.$set(this.portfel, item.id, response.data.portfel)
                    this.$set(this.trading_history, item.id, response.data.trading_history)
                    this.portfel_calc();
                    this.$forceUpdate();
                }).catch((error) => {
                    let text = 'При покупке произошла ошибка';
                    let title = `Ошибка`;
                    let variant =  'danger';
                    this.showToast(text, title, variant);
                })
            },
            sellStock(item){
                axios.post('/commands/'+this.command.id+'/stocks/'+item.id+'/sell', {
                    count: this.buy[item.id]
                }).then((response) => {
                    this.buy[item.id] = 0
                    this.command.balance = response.data.command.balance
                    this.command.stocks_balance = response.data.command.stocks_balance
                    this.command.stocks_count = response.data.command.stocks_count

                    this.$set(this.portfel, item.id, response.data.portfel)
                    this.$set(this.trading_history, item.id, response.data.trading_history)
                    this.portfel_calc();
                    this.$forceUpdate();
                }).catch((error) => {
                    let text = 'При продаже произошла ошибка';
                    let title = `Ошибка`;
                    let variant =  'danger';
                    this.showToast(text, title, variant);
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

                        this.command.balance = response.data.command.balance
                        this.command.stocks_balance = response.data.command.stocks_balance
                        this.command.stocks_count = response.data.command.stocks_count

                        this.prices[item.id] = response.data.price
                        this.portfel[item.id] = response.data.portfel
                        this.trading_history[item.id] = response.data.trading_history
                        this.portfel_calc();
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

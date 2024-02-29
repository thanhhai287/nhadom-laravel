<template>
    <div class="modal" v-if="isShowCheckout">
        <div class="modal-content p-0 border-0">
            <div class="modal-header" style="background-color: #0e873f">
                <h5 class="modal-title text-white" id="modal_demo_label">Xác nhận thanh toán</h5>
            </div>
            <div class="modal-body">
                <div class="row" style="min-height: 30vh">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table" style="width: 100%!important;">
                                <tr>
                                    <th style="border: 0; font-weight: 400">Tổng {{quantityItem}} sản phẩm</th>
                                    <td  class="text-right" style="border: 0">{{ formatPrice(totalPrice) }}</td>
                                </tr>
                                <br>
                                <tr>
                                    <th  class="mt-5" style="font-weight: 500">Tổng cộng</th>
                                    <td class="text-right" style="font-weight: 500;">
                                        {{ formatPrice(totalPrice)}}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="font-weight: 500; border: 0">Khách trả</th>
                                    <td  class="text-right" style="border: 0">
                                        <input
                                               style="min-width: 30px;max-width: 100px; color: #2f5cdb; font-weight: 500;"
                                               type="text" class="text-right"
                                               v-model="formattedPaid">
                                    </td>
                                </tr>

                                <tr v-if="paid > totalPrice">
                                    <th  class="mt-5" style="font-weight: 500; border: 0">Tiền thừa</th>
                                    <td class="text-right" style="border: 0">
                                        {{ formatPrice(paid - totalPrice)}}
                                    </td>
                                </tr>
                                <tr>
                                <div class="d-flex" style="padding: 12px 0px 12px 12px; width: 100%">
                                    <div class="mr-5" v-on:click="selectedPaymentMethod(1)">
                                        <input style="width: 16px; height: 16px" class="mr-2" type="radio" id="cash" value="Cash" v-model="paymentMethod" />
                                        <label for="cash">Tiền mặt</label>
                                    </div>

                                    <div v-on:click="selectedPaymentMethod(2)">
                                        <input style="width: 16px; height: 16px" class="mr-2" type="radio" id="bank" value="Bank Transfer" v-model="paymentMethod" />
                                        <label for="Bank">Chuyển khoản</label>
                                    </div>
                                </div>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #fff; color: black; min-height: 50px; width: 100px; border: 1px solid #0e873f" v-on:click="showCheckout">Quay lại</button>
                <button type="submit" class="btn " :disabled="paid < totalPrice" style="background-color: #0e873f; color: #fff; min-height: 50px; width: 100px" v-on:click="saleStore">Xác nhận</button>
            </div>
        </div>
    </div>
    <div >
        <div class="card border-0" style="background-color: transparent; height: 100%">
            <div class="card-body p-0">
                <div class="row position-relative" style="margin-left: 0; height: 100%">
                    <div class="col-xl-1 col-lg-2 col-sm-2 col-md-2 col-2 card p-0"
                         style="height: 100%!important; background-color: #fff; overflow: auto; -ms-overflow-style: none;  scrollbar-width: none; border: none; margin: 0">
                        <div class="form-row">
                            <div class="form-group">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist"
                                         style="border: 0; font-size: 1rem; line-height: 1.5rem">
                                        <a style="width: 100%; padding-left: 35px; border: 0"
                                           class="nav-item nav-link text-dark" id="nav-all-tab" data-toggle="tab"
                                           href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true"
                                           :class="[categoryId === 0 ? 'active' : '']"
                                           v-on:click="selectedCategory(null)">Tất cả</a>
                                        <a v-for="category in categories"
                                           style="width: 100%; padding-left: 35px; border: 0"
                                           class="nav-item nav-link text-dark" data-toggle="tab" role="tab"
                                           aria-selected="false"
                                           v-on:click="selectedCategory(category)">{{ category.category_name }}</a>
                                    </div>
                                </nav>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 col-md-6 col-sm-6 col-6"
                         style="height: 100%; overflow: auto; -ms-overflow-style: none;  scrollbar-width: none; padding: 0!important;">
                        <div class="row" style="padding-top: 20px; margin: 0">
                            <div v-for="product in products_list" class="col-lg-3 col-md-4 col-xl-2 col-sm-6 col-6"
                                 style="cursor: pointer; border-radius: 5px">
                                <div class="card border-0 shadow " style="max-height: 140px; max-width: 150px"
                                     v-on:click="addProductToShop(product)">
                                    <div class="position-absolute text-white" style="padding:5px; right: 0; z-index: 1000; background-color: #6C7D8C; opacity: 0.8; border-radius: 5px">
                                        <span style="font-size: 0.85rem; font-weight: 600">{{formatPrice(product.product_price)}}</span>
                                    </div>
                                    <div class="position-relative">
                                        <!-- :src="`${product.image}`"-->
                                        <img style="height: 80px; width: 100%;object-fit: cover; "
                                             :src="`${product.image}`"
                                             class="card-img-top" alt="Product Image">
                                    </div>
                                    <div class="card-body" style="width: 100%; padding: 0; text-align: center; height: 60px">
                                            <h6 style="font-size: 14px"
                                                class="card-title mb-0 mt-2">{{ product.product_name }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sd-4 col-4 card p-0"
                         style="height: 100%!important; background-color: #fff; overflow: auto; -ms-overflow-style: none;  scrollbar-width: none; border: none; margin: 0">
                        <div class="card border-0 shadow-sm" style="height: 100%!important; margin-bottom: 0">
                            <div class="card-body" style="padding: 0">
                                <div class="d-flex justify-content-around order-list"
                                     style="height: 70%; overflow: auto;  border-bottom: 5px solid #ebedef;">
                                    <div class="table-responsive">
                                        <table class="table" style="width: 100%!important;">
                                            <tbody>
                                            <tr v-if="shopItems.length > 0" v-for="item in shopItems">
                                                <td class="align-middle d-flex flex-column">
                                                    <div style="font-weight: 600;">
                                                        {{ item.product_name }}
                                                    </div>
                                                    <div class="mt-3 mb-3" style="width: 100%" v-if="item.isShowNote">
                                                        <input  placeholder="Nhập ghi chú"
                                                               style="min-height: 30px; color: #2f5cdb; min-width: 80%; outline: 2px solid #14b955!important; border-radius: 5px!important;"
                                                               type="text"
                                                               v-model="item.note">
                                                    </div>
                                                    <div class="d-flex justify-content-around mt-3">
                                                        <div class="d-flex">
                                                            <a v-on:click="removeItem(item)" style="cursor: pointer">
                                                                <i class="bi bi-trash font-xl text-dark mr-2"></i>
                                                            </a>
                                                            <a v-on:click="showNote(item)" class="ml-1" style="cursor: pointer">
                                                                <i class="bi bi-pencil-square font-xl "></i>
                                                            </a>
                                                        </div>
                                                        <form>
                                                            <div
                                                                class="input-group d-flex justify-content-between align-items-center">
                                                                <div class="input-group-prepend">
                                                                    <a class="btn shadow-none"
                                                                            v-on:click="decreaseItem(item)">
                                                                        <i class="bi bi-dash-lg"></i>
                                                                    </a>
                                                                </div>
                                                                <input readonly
                                                                       style="min-width: 30px;max-width: 60px; color: #2f5cdb"
                                                                       type="text" class="text-center"
                                                                       v-model="item.quantity">
                                                                <div class="input-group-append">
                                                                    <a class="btn shadow-none"
                                                                            v-on:click="increaseItem(item)">
                                                                        <i class="bi bi-plus-lg "
                                                                           style="color: green;"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <span style="color: #2f5cdb; font-weight: 500; margin-right: 20px">{{ formatPrice(item.product_price)  }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="shopItems.length <= 0" style="border: 0">
                                                <td colspan="8" class="text-center">
                                                    <svg width="248" height="248" viewBox="0 0 248 248" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M62.0179 217H185.979C189.386 217.008 192.671 215.721 195.174 213.396C205.617 203.669 213.971 191.896 219.723 178.801C225.475 165.705 228.502 151.565 228.621 137.248C229.155 79.036 182.118 31.1377 124.252 31.0003C66.3536 30.8628 19.3751 78.0401 19.3751 136.256C19.3586 150.745 22.3241 165.081 28.0851 178.361C33.8462 191.641 42.2778 203.577 52.8492 213.417C55.3471 215.731 58.622 217.011 62.0179 217Z" fill="#E6EAED"></path><path d="M200.944 74.1415L85.3408 74.4231C80.1854 74.4362 76.015 78.6257 76.0279 83.7821L76.3118 200.288C76.3247 205.444 80.5145 209.614 85.6706 209.602L201.274 209.32C206.429 209.307 210.599 205.118 210.587 199.961L210.303 83.4544C210.29 78.2991 206.1 74.1286 200.944 74.1415Z" fill="#BEC4CC"></path><path d="M162.905 74.2343L47.3018 74.5158C42.1454 74.5287 37.976 78.7185 37.9889 83.8748L38.2726 200.382C38.2857 205.537 42.4754 209.707 47.6316 209.695L163.234 209.413C168.39 209.4 172.559 205.21 172.548 200.054L172.263 83.547C172.251 78.3918 168.06 74.2212 162.905 74.2343Z" fill="#D5DBE0"></path><path d="M107.299 124.773L95.183 120.652L83.0885 124.831C82.3851 125.074 81.634 125.146 80.898 125.04C80.1622 124.934 79.4617 124.655 78.8556 124.224C78.2493 123.794 77.7542 123.224 77.413 122.563C77.0705 121.903 76.8912 121.17 76.8902 120.427L76.7774 74.4129L113.364 74.3238L113.475 120.337C113.477 121.081 113.302 121.815 112.963 122.476C112.625 123.139 112.132 123.711 111.529 124.144C110.924 124.578 110.225 124.861 109.489 124.971C108.754 125.08 108.003 125.012 107.299 124.773Z" fill="#5EB281"></path><path d="M118.674 173L52.3261 173C50.4892 173 49 174.463 49 176.267L49 194.733C49 196.537 50.4892 198 52.3261 198L118.674 198C120.511 198 122 196.537 122 194.733L122 176.267C122 174.463 120.511 173 118.674 173Z" fill="#FEFEFE"></path><path opacity="0.3" d="M114.733 179L72.2676 179C71.0155 179 70.0005 180.119 70.0005 181.5C70.0005 182.881 71.0155 184 72.2676 184L114.733 184C115.985 184 117 182.881 117 181.5C117 180.119 115.985 179 114.733 179Z" fill="#BEC4CC"></path><path opacity="0.3" d="M106.708 188L72.2918 188C71.0261 188 70 188.895 70 190C70 191.105 71.0261 192 72.2918 192L106.708 192C107.974 192 109 191.105 109 190C109 188.895 107.974 188 106.708 188Z" fill="#BEC4CC"></path><path opacity="0.3" d="M59.5 192C63.0899 192 66 189.09 66 185.5C66 181.91 63.0899 179 59.5 179C55.9102 179 53 181.91 53 185.5C53 189.09 55.9102 192 59.5 192Z" fill="#BEC4CC"></path><path d="M140.597 123.708L132.586 131.718L162.73 161.861L170.74 153.851L140.597 123.708Z" fill="#BEC4CC"></path><path d="M48.4879 44.4881C39.6601 53.3158 33.6485 64.563 31.2131 76.8071C28.7772 89.0516 30.0274 101.743 34.8048 113.277C39.5826 124.811 47.6731 134.67 58.0533 141.605C68.4335 148.541 80.6373 152.243 93.1216 152.243C105.606 152.243 117.81 148.541 128.19 141.605C138.57 134.67 146.66 124.811 151.438 113.277C156.216 101.743 157.466 89.0516 155.03 76.8071C152.595 64.563 146.583 53.3158 137.755 44.4881C131.894 38.6267 124.935 33.9772 117.277 30.805C109.619 27.6328 101.411 26 93.1216 26C84.8325 26 76.6242 27.6328 68.9663 30.805C61.3078 33.9772 54.3493 38.6267 48.4879 44.4881ZM129.523 125.523C122.323 132.722 113.15 137.625 103.165 139.612C93.1787 141.598 82.8281 140.578 73.4215 136.682C64.015 132.786 55.9748 126.188 50.3183 117.722C44.6618 109.256 41.6427 99.3034 41.6427 89.1218C41.6427 78.9398 44.6618 68.9868 50.3183 60.5214C55.9748 52.0555 64.015 45.4573 73.4215 41.561C82.8281 37.6647 93.1787 36.6451 103.165 38.6315C113.15 40.6179 122.324 45.5208 129.523 52.7205C139.177 62.3746 144.6 75.4687 144.6 89.1218C144.6 102.775 139.177 115.869 129.523 125.523Z" fill="#BEC4CC"></path><path d="M146.955 138.454C141.935 143.474 141.935 151.613 146.955 156.632L165.983 175.661C171.003 180.68 179.142 180.68 184.162 175.661C189.181 170.641 189.181 162.502 184.162 157.482L165.133 138.454C160.114 133.434 151.975 133.434 146.955 138.454Z" fill="#E6EAED"></path></svg>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div v-if="shopItems.length > 0" class="d-flex flex-column order-checkout"
                                     style="position: absolute; bottom: 10px; margin: 0; width: 100%; text-align: center; height: 30%;">
                                    <div class="d-flex justify-content-between" style="padding: 20px; margin-right: 40px">
                                        <span style="border: 0; margin-left: 15px">Tổng {{quantityItem }} sản phẩm</span>
                                        <span class="text-right"
                                              style="border: 0">{{ formatPrice(totalPrice) }}</span>
                                    </div>
                                    <div class="form-row" style="margin-right: 50px!important">
                                        <div class="form-group d-flex justify-content-end flex-wrap mb-0"
                                             style="width: 100%; margin: 0px 0px 5px 0px!important; align-items: center;">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #fff; color: black; height: 60px; width: 130px; border: 1px solid #0e873f; margin: 1px 5px!important;" v-on:click="captureAndSave">Chụp ảnh hóa đơn</button>
                                            <button
                                                style="background-color: #0e873f; color: #fff; height: 60px; font-size: 1.1rem; margin: 1px 5px!important; width: 130px"
                                                type="button" class="btn btn-pill ml-4" v-on:click="showCheckout">Thanh toán<br>{{ formatPrice(totalPrice) }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row position-relative" ref="screenshot" style="opacity: 0;  background-color: #fff">
                    <div class="col-4 card p-0" ref="screenshotElement"
                         style="background-color: #fff; overflow: auto; -ms-overflow-style: none;  scrollbar-width: none; border: none; margin: 0">
                        <div class="card border-0 shadow-sm" style="margin-bottom: 0; background-color: #fff;">
                            <div class="card-body" style="padding: 0">
                                <div class="d-flex justify-content-around">
                                    <div class="table-responsive" style="background-color: #fff">
                                        <table class="table" style="width: 100%!important;">
                                            <tbody>
                                            <tr v-if="shopItems.length > 0" v-for="item in shopItems">
                                                <td class="align-middle d-flex justify-content-between ">
                                                    <div style="font-weight: 600;">
                                                        {{ item.product_name }}
                                                    </div>
                                                    <div style="display: flex; justify-content: flex-end">
                                                        <span style="color: #2f5cdb; font-weight: 500; margin-right: 10px">{{item.quantity}}</span>
                                                        <span style="font-weight: 500; margin-right: 10px">x</span>
                                                        <span style="color: #2f5cdb; font-weight: 500; margin-right: 10px">{{ formatPrice(item.product_price)  }}</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 0">
                                                    <div v-if="shopItems.length > 0" class="d-flex flex-column">
                                                        <div class="d-flex justify-content-between">
                                                            <h5 style="border: 0; margin-left: 15px; margin-right: 30px">Tổng {{quantityItem }} sản phẩm</h5>
                                                            <h5 class="text-right"
                                                                style="border: 0; margin-right: 30px">{{ formatPrice(totalPrice) }}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div >
                </div>
            </div>
        </div>
    </div>
</template>

<script >
import html2canvas from 'html2canvas';
export default {
    props: ['products', 'categories', 'sale_store'],
    mounted() {
       // console.log(this.products)
        // Do something useful with the data in the template
        var shopItemsInStorage = localStorage.getItem('shopItems')
        if (shopItemsInStorage) {
            var parsedShopItems = JSON.parse(shopItemsInStorage);
            console.log(parsedShopItems)
            this.shopItems = parsedShopItems;
        }

    },
    data() {
        return {
            // Initialized to zero to begin
            shopItems: [],
            products_list: this.products,
            loader: false,
            isShowCheckout: false,
            paid: 0,
            paymentMethod: 'Cash',
            categoryId: 0,
        }
    },
    computed: {
        async requestDevice() {
            var bluetooth = null;
            // shared UUIDs between MicroPython and JS
            const UART_SERVICE_ID = '6E400001-B5A3-F393-E0A9-E50E24DCCA9E'.toLowerCase();
            const CHARACTERISTIC_TX = '6E400003-B5A3-F393-E0A9-E50E24DCCA9E'.toLowerCase();
            const CHARACTERISTIC_RX = '6E400002-B5A3-F393-E0A9-E50E24DCCA9E'.toLowerCase();


            console.log('Requesting any Bluetooth Device...');
            var device = await navigator.bluetooth.requestDevice({
                // filters: [...] <- Prefer filters to save energy & show relevant devices.
                acceptAllDevices: true,
                optionalServices: [ UART_SERVICE_ID ] // TODO
            });

            console.log("Device connected");
        },

        totalPrice: function () {
            var total = 0;
            $.each(this.shopItems, function (index, item) {
                total += item.product_price * item.quantity;
            });
            return total;
        },
        quantityItem: function () {
            var quantity = 0;
            $.each(this.shopItems, function (index, item) {
                quantity += quantity + item.quantity > 1 ? item.quantity : 1;
            });
            localStorage.removeItem('shopItems');
            localStorage.setItem('shopItems', JSON.stringify(this.shopItems));
            return quantity;
        },
        formattedPaid: {
            get() {
                // Format the amount with comma separators
                let val = (this.paid/1).toFixed(0).replace('.', ',')
                return  val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            set(value) {
                // Parse the value and update the data property
                const parsedValue = parseFloat(value.replace('.', ''));
                this.paid = isNaN(parsedValue) ? 0 : parsedValue;
            }
        }
    },
    methods: {
        formatPrice(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return  val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        selectedCategory(category) {
            if (category != null) {
                this.categoryId = category.id;
                this.products_list = this.products.filter(product => product.category_id === category.id);
            } else {
                this.categoryId = 0;
                this.products_list = this.products;
            }
        },
        removeItem(item) {
            const index = this.shopItems.indexOf(item);
            if (index > -1) {
                this.shopItems.splice(index, 1);
            }
            if (this.shopItems.length <= 0) {
                localStorage.removeItem('shopItems');
            }
        },
        addProductToShop(product) {
            var existProduct = this.shopItems.find(item => item.id == product.id);
            if (existProduct === undefined) {
                this.shopItems.push({
                    'id': product.id,
                    'quantity': 1,
                    'product_price': product.product_price,
                    'product_name': product.product_name,
                    'isShowNote': false,
                    'item.note' : ""
                })
            } else {
                existProduct.quantity += 1;
            }
        },
        increaseItem(item) {
            item.quantity++;
        },

        decreaseItem(item) {
            item.quantity--;
            if (item.quantity === 0) {
                this.removeItem(item);
            };
        },

        async saleStore(){
            var data = [];
            data.push({
                'shopItems' : this.shopItems,
                'paid_amount' : this.totalPrice,
                'total_amount' : this.totalPrice,
                'payment_method': this.paymentMethod
            })
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var thiz = this;
            $.ajax({
                url: thiz.sale_store,
                type: "POST",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: JSON.stringify(data),
                success: function (data) {
                    localStorage.clear();
                    location.reload()
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Lỗi " + jqXHR.status + " " + textStatus);
                }
            });
        },
        showCheckout() {
            this.isShowCheckout = !this.isShowCheckout;
            this.paid = this.totalPrice;
        },
        selectedPaymentMethod(idx) {
            this.paymentMethod = idx === 2 ? 'Bank Transfer' : 'Cash';
        },
        showNote(item) {
            item.isShowNote = !item.isShowNote
        },
        async captureAndSave() {
            const screenshot = this.$refs.screenshot;
            screenshot.style.display = 'flex';
            if (screenshot.style.display != 'none' ) {
                const element = this.$refs.screenshotElement;
                try {
                    html2canvas(element).then((canvas) => {
                        screenshot.style.display = 'none';
                        const context = canvas.getContext('2d');
                        // Draw the content of the div onto the canvas
                        context.drawImage(canvas, 0, 0, 348, 40);

                        // Convert the canvas content to a data URL
                        const dataURL = canvas.toDataURL('image/png');
                        console.log(dataURL)
                        const downloadLink = document.createElement('a');
                        downloadLink.href = dataURL;
                        downloadLink.download = 'image.jpg';

                        // Simulate a click on the link to trigger the download
                        downloadLink.click();
                    });
                } catch (error) {
                    console.error('Error capturing screenshot:', error);
                }
            }

        }
    }
}
</script>

<style scoped>
/* Styles for modal */
    @keyframes rightToLeft {
        0% {
            opacity: 0;
            transform: translate3d(100%, 0, 0);
        }
        100% {
            opacity: 1;
            transform: none;
        }
    }

.modal-content {
    position: fixed;
    right: 0;
    top:56px;
    bottom: 0;
    margin-top: 0!important;
    width: 45%!important;
    animation-name: rightToLeft;
    animation-duration: 0.3s;
    background-color: #fefefe;
    padding: 20px;
    border: 1px solid #888;
}
.modal {
    display: block!important;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

</style>

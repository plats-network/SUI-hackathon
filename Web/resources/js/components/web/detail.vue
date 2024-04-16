<template>
    <div>
        <div class="demo-image">
            <div class="block">
                <img
                    style="width: 100%; height: auto ;border-radius: 20px"
                    :src="detail.banner_url"
                    >
            </div>
        </div>
        <div>
            <p class="error">{{ error }}</p>
            <!-- TODO: QRcode -->
            <!-- <p class="decode-result" style="font-size:150%;">
                QRコードリーダー<br>
                <b style="color:#f00;">{{ result }}</b>
            </p>
            <qrcode-stream @decode="onDecode" @init="onInit" /> -->
        </div>
        <div class="mt-3">
            <el-row :gutter="10">
                <el-col :xs="24" :sm="24" :md="16" :lg="16" :xl="16">
                    <div>
                        <span class="title-event-detail"> {{detail.name}}</span>
                    </div>
                    <div v-show='toggle'>
                        <div class="mt-3 mb-3">
                            <el-row :gutter="10">
                                <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                    <div class="mb-2"><i class="el-icon-date" style="color: #409EFF"></i> Date and time
                                    </div>
                                    <div>
                                        <span class="d-block">{{detail.start_at | moment}} - {{detail.end_at | moment}}</span>
                                    </div>
                                </el-col>
                                <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                    <div class="mb-2"><i class="el-icon-location-information"
                                                         style="color: #409EFF"></i> Location
                                    </div>
                                    <div>{{detail.address}}
                                    </div>
                                </el-col>
                            </el-row>
                        </div>
                        <!-- <div class="mb-3"> -->
<!--                            <gmap-map-->
<!--                                :zoom="14"-->
<!--                                :center="center"-->
<!--                                style="width:100%;  height: 300px;"-->
<!--                            >-->
<!--                                <gmap-marker-->
<!--                                    :key="index"-->
<!--                                    v-for="(m, index) in locationMarkers"-->
<!--                                    :position="m.position"-->
<!--                                    @click="center=m.position"-->
<!--                                ></gmap-marker>-->
<!--                            </gmap-map>-->
                        <!-- </div> -->
                        <!-- Desc -->
                        <div class="" v-html="detail.description"></div>
                        <div class=" mt-3 mb-3">
                            <div class="mb-2"><span class="fw-bold">Share with friends</span></div>
                            <div>
                                <ShareNetwork style="margin-right: 25px"
                                              network="facebook"
                                              :url="url"
                                              title="21212"
                                              description="1212121"
                                              hashtags="plats,skc"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-facebook" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                    </svg>
                                </ShareNetwork>
                                <ShareNetwork style="margin-right: 25px"
                                              network="email"
                                              :url="url"
                                              title=""
                                              description=""
                                              quote=""
                                              hashtags=""
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-envelope" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                    </svg>
                                </ShareNetwork>
                                <ShareNetwork
                                    network="twitter"
                                    :url="url"
                                    title=""
                                    description=""
                                    quote=""
                                    hashtags=""
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-twitter" viewBox="0 0 16 16">
                                        <path
                                            d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                                    </svg>
                                </ShareNetwork>
                            </div>
                        </div>
                    </div>
                    <el-button style="display: block;margin: auto;padding: 10px 100px;" type="primary"
                               @click='toggle = !toggle'>Hide
                    </el-button>
                    <!-- <div>
                        <el-tabs  v-model="activeName" @tab-click="handleClick">
                            <el-tab-pane  label="Booths" name="first">
                                <div style="height: 200px;">
                                    <el-steps direction="vertical">
                                        <el-step v-for="(booth, index) in this.detail.booths.detail" :title="booth.name" ></el-step>
                                    </el-steps>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane  label="Sessions" name="second">
                                <div style="height: 200px;">
                                    <el-steps direction="vertical">
                                        <el-step v-for="(session, index) in this.detail.sessions.detail" :title="session.name" ></el-step>
                                    </el-steps>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </div> -->
<!--                    <div class="mb-5">-->
<!--                        <div class="">-->
<!--                            <div class="mt-3" style="font-weight: 800;font-size: 20px;color: #545454;"><a href="/events">Other events</a>-->
<!--                            </div>-->
<!--                            <el-row>-->
<!--                                <el-col :md="6" :sm="12" :xs="12" :lg="6" :xl="6" class="p-1" v-for="(event, index) in otherEvent">-->
<!--                                    <el-card shadow="hover" :body-style="{ padding: '3px' }">-->
<!--                                        <img-->
<!--                                            :src="event.banner_url"-->
<!--                                            class="image w-100" style="height: 150px">-->
<!--                                        <div style="padding: 10px 5px;">-->
<!--                                            <span style="font-weight: 800;font-size: 16px"><a  v-bind:href="'/events/'+ event.slug" style="color:black;">{{event.name}}</a></span>-->
<!--                                            <div class="">-->
<!--                                                <el-button type="text" class="button"><i class="el-icon-time"></i> <span-->
<!--                                                    style="font-size: 12px">{{event.end_at | moment}}</span>-->
<!--                                                </el-button>-->
<!--                                                <span class="d-block">{{event.description | truncate(100)}}</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </el-card>-->
<!--                                </el-col>-->
<!--                            </el-row>-->
<!--                        </div>-->
<!--                    </div>-->
                </el-col>
                <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
                    <el-card class="box-card fixed" shadow="hover" style="border-radius: 20px">
                        <span style="display: block;text-align: center" class="mb-2">Free</span>
                        <el-button @click="dialogFormVisible = true" style="display:block;margin: auto;padding: 12px 80px" type="primary">Get tickets
                        </el-button>
                    </el-card>
                </el-col>
            </el-row>
            <el-dialog title="Get ticket" :visible.sync="dialogFormVisible" style="width: 90%; margin: 0 auto;">
                <el-form :model="form" ref="form"  :rules="rules" label-position="top" style="width: 100%">
                    <el-form-item prop="name" label="Tên" :label-width="formLabelWidth">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Email" prop="email" :label-width="formLabelWidth">
                        <el-input v-model="form.email" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Số điện thoại" prop="phone" :label-width="formLabelWidth">
                        <el-input v-model="form.phone" autocomplete="off"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">Cancel</el-button>
                <el-button type="primary" @click="submitForm('form')">Check out</el-button>
                </span>
            </el-dialog>
        </div>
    </div>
</template>

<script>
import 'element-tiptap/lib/index.css';
import {Notification} from 'element-ui';
import {ElementTiptap} from 'element-tiptap';
import {
    Doc,
    Text,
    Paragraph,
    Heading,
    Bold,
    Underline,
    Italic,
    Strike,
    ListItem,
    BulletList,
    OrderedList,
    Image,
    TodoList,
    TodoItem,
    Iframe,
    Table,
    TableHeader,
    TableRow,
    TableCell,
    TextAlign,
    LineHeight,
    Indent,
    HorizontalRule,
    HardBreak,
    TrailingNode,
    History,
    TextColor,
    TextHighlight,
    FormatClear,
    FontType,
    FontSize,
    Preview,
    CodeView,
    Print,
    Fullscreen,
    SelectAll,
} from 'element-tiptap';
import moment from "moment";

export default {
    name: "detail",
    props: ['detail_id','key_local'],
    data() {
        return {
            result: '',
            error: '',
            rules: {
                name: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                email: [
                    { required: true, message: 'Please input email address', trigger: 'blur' },
                    { type: 'email', message: 'Please input correct email address', trigger: ['blur', 'change'] }
                ]
            },
            center: {
                lat: 39.7837304,
                lng: -100.4458825
            },
            url: window.location.href,
            dialogFormVisible: false,
            toggle: true,
            activeName: 'first',
            locationMarkers: [],
            locPlaces: [],
            existingPlace: null,
            form: {
                name: '',
                email: '',
                phone: '',
                task_id: '',
                share: this.key_local,

            },
            props: {
                idTask:'',
                key_local:'',
            },
            detail:{
                booths:{
                    detail:[

                    ]
                },
                sessions:{
                    detail:[

                    ]
                }
            },
            otherEvent:[],
            formLabelWidth: '120px'
        }
    },
    methods: {
        onDecode (result) {
            this.result = result
        },

        async onInit (promise) {
            try {
                await promise
            } catch (error) {
                if (error.name === 'NotAllowedError') {
                    this.error = "ERROR: you need to grant camera access permisson"
                } else if (error.name === 'NotFoundError') {
                    this.error = "ERROR: no camera on this device"
                } else if (error.name === 'NotSupportedError') {
                    this.error = "ERROR: secure context required (HTTPS, localhost)"
                } else if (error.name === 'NotReadableError') {
                    this.error = "ERROR: is the camera already in use?"
                } else if (error.name === 'OverconstrainedError') {
                    this.error = "ERROR: installed cameras are not suitable"
                } else if (error.name === 'StreamApiNotSupportedError') {
                    this.error = "ERROR: Stream API is not supported in this browser"
                }
            }
        },
        submitForm(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    axios.post('/events/ticket', this.form).then(e => {
                        Notification.success({
                            title: 'Message',
                            message: 'Get ticket successfully!',
                            type: 'success',
                        });
                        window.open('/events/download-ticket/'+this.form.task_id, '_blank');
                        loading.close();
                        this.dialogFormVisible = false
                    }).catch(error => {
                        this.errors = error.response.data.message;
                        Notification.error({
                            title: 'Error',
                            message: this.errors,
                            type: 'error',
                        });
                        loading.close();
                    });
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        handleClick(tab, event) {
            console.log(tab, event);
        },
        initMarker(loc) {
            this.existingPlace = loc;
        },
        locateGeoLocation: function () {
            navigator.geolocation.getCurrentPosition(res => {
                this.center = {
                    lat: res.coords.latitude,
                    lng: res.coords.longitude
                };
            });
        },
        getDetail(){
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/events/task/'+ this.props.idTask;
            axios.get(url).then(e => {
                this.detail = e.data.message;
                this.form.task_id = this.detail.id
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        // getOtherEvents() {
        //     let rawData =
        //         {
        //             'limit': 4,
        //         }
        //     const loading = this.$loading({
        //         lock: true,
        //         text: 'Loading',
        //         spinner: 'el-icon-loading',
        //         background: 'rgba(0, 0, 0, 0.7)'
        //     });
        //     let url = '/api/cws/events/list'
        //     axios.get(url,{
        //         params: rawData
        //     }).then(e => {
        //         this.otherEvent = e.data.data;
        //         loading.close();
        //
        //     }).catch((_) => {
        //         loading.close();
        //     })
        // }
    },
    mounted() {
        this.props.idTask = this.detail_id
        this.props.key_local = this.key_local
        console.log(this.key_local)
        this.getDetail();
        // this.getOtherEvents();// this.locateGeoLocation();
    },
    filters: {
        moment: function (date) {
            return moment(date).format(' YYYY-MM-DD HH:mm:ss ');
        },
    }
}
</script>

<style>
@media (max-width: 480px) {
    .fixed {
        position: fixed;
        bottom: 0;
        width: 90%;
        z-index: 1;
        padding: 0;
    }
    .el-dialog{
        width: 100%;
    }
}


@media (max-width: 992px) {
    .fixed {
        position: fixed;
        bottom: 0;
        width: 90%;
        z-index: 1;
    }
    .el-dialog{
        width: 100%;
    }
}

.title-event-detail {
    font-weight: 800;
    font-size: 35px;
}

.el-col {
    border-radius: 4px;
}

.bg-purple-dark {
    background: #99a9bf;
}

.bg-purple {
    background: #d3dce6;
}

.bg-purple-light {
    background: #e5e9f2;
}

.grid-content {
    border-radius: 4px;
    min-height: 36px;
}
</style>

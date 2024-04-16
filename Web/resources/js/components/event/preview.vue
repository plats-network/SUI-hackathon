<template>
    <div>
        <div class="mb-2">
            <a href="/events"  ><el-button type="primary" icon="el-icon-back"></el-button></a>
            <el-button type="primary" style="float: right" @click="handleEditEvent">Edit</el-button>
        </div>
        <el-row :gutter="20">
            <el-col :span="10"  >
                <div  class="p-2" style="border: 1px solid #EBEEF5">
<!--                    <div v-show="form.task_galleries.length > 0">-->
<!--                        <span>Slider</span>-->
<!--                        <el-carousel indicator-position="outside">-->
<!--                            <el-carousel-item  v-for="(event, index) in form.task_galleries" >-->
<!--                                <div>-->
<!--                                    <el-image :src="event.url" class=""></el-image>-->
<!--                                </div>-->
<!--                            </el-carousel-item>-->
<!--                        </el-carousel>-->
<!--                    </div>-->
                    <div class="demo-image">
                        <span>Image</span>
                        <div class="block">
                            <img class="w-100"
                                style="height: auto ;border-radius: 20px"
                                :src="form.banner_url"
                            >
                        </div>
                    </div>
                    <div class="mt-3">
                        <el-row :gutter="10">
                            <el-col>
                                <div>
                                    <span class=""> Title :  {{form.name}}</span>
                                </div>
                                <div>
                                    <div class="mt-3 mb-3">
                                        <el-row :gutter="10">
                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                <div class="mb-2"> Time : {{form.start_at | moment}} - {{form.end_at | moment}}
                                                </div>
                                            </el-col>
                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                <div class="mb-2"> Location : {{form.address}}
                                                </div>
                                            </el-col>
                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                <div class="mb-2"> Order : {{form.order}}
                                                </div>
                                            </el-col>
                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                <div class="mb-2"> Lat : {{form.lat}}  Long : {{form.lng}}
                                                </div>

                                            </el-col>
                                        </el-row>
                                    </div>
                                    <div> Description :
                                        <div class="" v-html="form.description"></div>
                                    </div>
                                </div>
                            </el-col>
                        </el-row>
                    </div>
                </div>
            </el-col>
            <el-col  :span="14" >
                <div class="p-2" style="border: 1px solid #EBEEF5">
                    <div class="row">
                        <div v-if="sessions !== null" class="col-md-6">
                            <span>Sessions</span>
                            <el-card shadow="hover" class="box-card mb-2">
                                <div slot="header" class="clearfix">
                                    <span><strong>Title :</strong>   {{ form.sessions.name }}</span>
                                    <br>
                                    <span><strong>Max Job :</strong> {{ form.sessions.max_job }}</span>
                                    <br>
                                    <span>
                                        <strong>Link Mini Game :</strong>
                                        <el-link :href="link_mini_game+'/session/'+form.sessions.code" target="_blank">Link</el-link>
                                    </span>
                                </div>
                                <div v-for="(detail, index) in form.sessions.detail" class="mb-1">
                                    <el-popover
                                        placement="right"
                                        trigger="click">
                                        <div ref="qrcode">
                                            <qrcode-vue :id="detail.id" :value="link_event+'/events/code?type=event&id='+detail.code" :size="size" level="H"></qrcode-vue>
                                            <button @click="downloadQrCode(detail.id)">Download</button>
                                        </div>
                                        <el-alert slot="reference" style="cursor: pointer;" class="hover-alert"
                                            :closable="false"
                                            :title="index+1 + ' : ' + detail.name"
                                            type="info"
                                        >
                                        </el-alert>
                                    </el-popover>
                                </div>
                            </el-card>
                        </div>
                        <div v-if="booths !== null" class="col-md-6">
                            <span>Booths</span>
                            <el-card shadow="hover" class="box-card mb-2">
                                <div slot="header" class="clearfix">
                                    <span><strong>Title :</strong> {{ form.booths.name }}</span>
                                    <br>
                                    <span><strong>Max Job :</strong> {{ form.booths.max_job }}</span>
                                    <br>
                                    <span><strong>Link Mini Game :</strong>
                                        <el-link :href="link_mini_game+'/booth/'+form.booths.code" target="_blank">Link</el-link>
                                    </span>
                                </div>
                                <div v-for="(detail, index) in form.booths.detail" class="text item mb-1">
                                    <el-popover
                                        placement="right"
                                        trigger="click">
                                        <div ref="qrcode">
                                            <qrcode-vue :id="detail.id" :value="link_event+'/events/code?type=event&id='+detail.code" :size="size" level="H"></qrcode-vue>
                                            <button @click="downloadQrCode(detail.id)">Download</button>
                                        </div>
                                        <el-alert slot="reference" style="cursor: pointer;" class="hover-alert"
                                                  :closable="false"
                                                  :title="index+1 + ' : ' + detail.name"
                                                  type="info"
                                        >
                                        </el-alert>
                                    </el-popover>
                                </div>
                            </el-card>
                        </div>
                        <div v-if="quiz.length > 0" class="col-md-6">
                            <span>Quiz</span>
                            <el-card shadow="hover" v-for="detail in form.quiz" class="box-card mb-2">
                                <div slot="header" class="clearfix">
                                    <span> Name : {{ detail.name }}</span>
                                </div>
                                <div v-for="detail in detail.detail" class="text item mb-1">
                                    <el-alert
                                        :closable="false"
                                        v-if="detail.status == true"
                                        :title="detail.name"
                                        type="success"
                                        show-icon>
                                    </el-alert>
                                    <el-alert
                                        :closable="false"
                                        v-if="detail.status == false"
                                        :title="detail.name"
                                        type="info"
                                        show-icon>
                                    </el-alert>
                                </div>
                            </el-card>
                        </div>
                        <div v-if="task_event_socials !== null" class="col-md-6">
                            <span>Social</span>
                            <el-card shadow="hover" class="box-card mb-2">
                                <div slot="header" class="clearfix">
                                    <el-row :gutter="20">
                                        <el-col>
                                            <el-checkbox v-model="form.task_event_socials.is_comment">Comment
                                            </el-checkbox>
                                            <el-checkbox v-model="form.task_event_socials.is_like">Like
                                            </el-checkbox>
                                            <el-checkbox v-model="form.task_event_socials.is_retweet">Retweet
                                            </el-checkbox>
                                            <el-checkbox v-model="form.task_event_socials.is_tweet">Tweet
                                            </el-checkbox>
                                        </el-col>
                                    </el-row>
                                </div>
                                <div>
                                    <div>
                                        <span> URL</span>
                                        <el-input class="mb-2 mt-2" maxlength="255" show-word-limit
                                                  placeholder="https://twitter.com/elonmusk/status/1638381090368012289"
                                                  v-model="form.task_event_socials.url" :disabled="true"></el-input>
                                    </div>
                                    <div>
                                        <span> Text</span>
                                        <el-input class="mb-2 mt-2" maxlength="255" show-word-limit
                                                  placeholder="Text ..."
                                                  v-model="form.task_event_socials.text" :disabled="true"></el-input>
                                    </div>
                                </div>
                            </el-card>
                        </div>
                        <div v-if="task_event_discords !== null" class="col-md-6">
                            <el-row>
                                <el-col >
                                    <el-card shadow="hover" class="box-card mb-2">
                                        <div slot="header" class="clearfix">
                                            <span>Discord</span>
                                        </div>
                                        <div class="row">
                                            <div>
                                                <span> Bot Token</span>
                                                <el-input class="mb-2 mt-2" :disabled="true"  placeholder=" Bot Token" v-model="task_event_discords.bot_token"></el-input>
                                            </div>
                                            <div class="col-md-6">
                                                <span> Channel Id</span>
                                                <el-input class="mb-2 mt-2" :disabled="true"  placeholder="Channel Id" v-model="task_event_discords.channel_id"></el-input>
                                            </div>
                                            <div class="col-md-6">
                                                <span> Channel Url</span>
                                                <el-input class="mb-2 mt-2" :disabled="true"  placeholder="Channel Url" v-model="task_event_discords.channel_url"></el-input>
                                            </div>
                                        </div>
                                    </el-card>
                                </el-col>
                            </el-row>
                        </div>
                        <div v-if="form.task_generate_links.length > 0 " class="col-md-6">
                            <el-card shadow="hover" class="box-card mb-2">
                                <div slot="header" class="clearfix">
                                    <span>Link Share</span>
                                </div>
                                <div>
                                    <div v-for="(detail, index) in form.task_generate_links" class="mb-1">
                                        <el-link :href="detail.url" style="background: #f4f4f5; padding: 5px" target="_blank">{{detail.url}}</el-link>
                                    </div>
                                </div>
                            </el-card>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import { ref } from 'vue'
import 'element-tiptap/lib/index.css';
import {Notification} from 'element-ui';
import QrcodeVue from 'qrcode.vue'
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
    name: "preview",
    props: ['task_id','link_cws','link_event','link_mini_game'],
    components: {
        'el-tiptap': ElementTiptap,
        QrcodeVue,
    },
    data() {
        return {
            size: 300,
            form : {
                id : '',
                banner_url : '',
                name : '',
                lat : '',
                lng : '',
                address : '',
                description : '',
                start_at : '',
                end_at : '',
                type : 1,
                order : '',
                task_galleries: [],
                task_generate_links:[],
            },
            sessions: {
                name:'',
                max_job:'',
                banner_url:'',
                description:'',
                type:0,
                code:'',
                detail:[
                    {
                        name: '',
                        description: ''
                    }
                ]
            },
            booths: {
                name:'',
                max_job:'',
                banner_url:'',
                description:'',
                type:1,
                code:'',
                detail:[
                    {
                        name: '',
                        description: ''
                    }
                ]
            },
            quiz :[
                {
                    name: '',
                    time_quiz: '',
                    order: '',
                    status: true,
                    detail:[
                        {
                            key:0,
                            name:'',
                            status:true
                        },
                        {
                            key:1,
                            name:'',
                            status:false
                        },
                        {
                            key:2,
                            name:'',
                            status:false
                        },
                        {
                            key:3,
                            name:'',
                            status:false
                        }
                    ]
                }
            ],
            task_event_socials:{
                url:'',
                text:'',
                is_comment:false,
                is_like:false,
                is_retweet:false,
                is_tweet:false,
                type:0,
            },
            task_event_discords:{
                bot_token:'',
                channel_id:'',
                channel_url:'',
            },
        }
    },
    methods: {
        downloadQrCode(id){
            let canvasImage = document.getElementById(id).querySelector('canvas').toDataURL('image/png');
            // this can be used to download any image from webpage to local disk
            let xhr = new XMLHttpRequest();
            xhr.responseType = 'blob';
            xhr.onload = function () {
                let a = document.createElement('a');
                a.href = window.URL.createObjectURL(xhr.response);
                a.download = 'qrc'+id+'.png';
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                a.remove();
            };
            xhr.open('GET', canvasImage); // This is to download the canvas Image
            xhr.send();
        },
        handleEditEvent(){
            window.location.href = '/events/edit/'+ this.task_id;
        },
        listData() {
            this.drawerEdit = true
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = this.link_cws+'/api/tasks-cws/edit/'+ this.task_id;
            axios.get(url).then(e => {
                this.form = e.data.data.message;
                this.quiz = e.data.data.message.quiz
                this.sessions = e.data.data.message.sessions
                this.task_event_socials = e.data.data.message.task_event_socials
                this.task_event_discords = e.data.data.message.task_event_discords
                this.booths = e.data.data.message.booths
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
    },
    mounted: function () {
        this.listData()
    },
    filters: {
        moment: function (date) {
            return moment(date).format(' YYYY-MM-DD HH:mm:ss ');
        },
    }
}
</script>

<style scoped>
.hover-alert:hover {
    background-color: #409EFF;
    color: white;
}
</style>

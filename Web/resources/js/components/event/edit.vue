<template>
    <div>
        <div style="display: flex;justify-content: space-between">
            <h4>Edit Event</h4>
            <h3><a href="/events" ><el-button type="primary" icon="el-icon-back"></el-button></a>
            </h3>
        </div>
        <div>
            <el-form ref="form" class="form-style formCreate" :rules="rules" label-position="top" :model="form" label-width="100px" >
                <el-row :gutter="20">
                    <el-row :gutter="20">
                        <el-col :span="16">
                            <el-form-item label="Name" prop="name">
                                <el-input v-model="form.name" placeholder="Name"></el-input>
                            </el-form-item>
                            <el-form-item label="Address" prop="address">
                                <el-input v-model="form.address" placeholder="address"></el-input>
                            </el-form-item>
                            <div class="row">
                                <div class="col-md-6">
                                    <el-form-item label="Lat" prop="lat">
                                        <el-input v-model="form.lat" placeholder="lat"></el-input>
                                    </el-form-item></div>
                                <div class="col-md-6">
                                    <el-form-item label="Lng" prop="lng">
                                        <el-input v-model="form.lng" placeholder="lng"></el-input>
                                    </el-form-item>
                                </div>
                            </div>
                            <el-form-item label="Description" prop="description">
                                <ckeditor v-model="form.description" ></ckeditor>
                            </el-form-item>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <el-form-item label="Order" prop="order">
                                            <el-input v-model="form.order" placeholder="Order" style="margin-right: 20px"></el-input>
                                        </el-form-item>
                                    </div>
                                    <div class="d-flex">
                                        <el-form-item label="Start At" style="margin-right: 20px" prop="start_at">
                                            <el-date-picker
                                                type="datetime" :picker-options="pickerOptions"
                                                placeholder="Select date and time"
                                                v-model="form.start_at"
                                                :min-date="new Date()"></el-date-picker>
                                        </el-form-item>
                                        <el-form-item label="End At" prop="end_at">
                                            <el-date-picker
                                                type="datetime" :picker-options="pickerOptions"
                                                placeholder="Select date and time"
                                                v-model="form.end_at"></el-date-picker>
                                        </el-form-item>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <el-checkbox v-model="form.is_paid">Paid</el-checkbox>
                                    <div class="d-flex mt-2" v-if="form.is_paid == true">
                                        <el-input v-model="form.reward" placeholder="reward" style="margin-right: 5px"></el-input>
                                        <el-select class="full-option" v-model="form.reward_type"
                                                   placeholder="Select">
                                            <el-option
                                                v-for="(value, key, index) in typeReward"
                                                :key="index"
                                                :label="value"
                                                :value="key">
                                            </el-option>
                                        </el-select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex mt-4">
                                <el-button @click="addSessions()">Sessions</el-button>
                                <el-button @click="addBooths()">Booths</el-button>
                                <el-button @click="addSocial()">Social</el-button>
                                <el-button @click="dialogQuiz = true">Quiz</el-button>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Image">
                                <el-upload
                                    class="avatar-uploader"
                                    :headers="{ 'X-CSRF-TOKEN': csrf }"
                                    :action="link_cws+'/tasks/save-avatar-api'"
                                    :on-success="handleAvatarSuccess"
                                    :before-upload="beforeAvatarUpload">
                                    <img v-if="form.banner_url" :src="form.banner_url" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="Slider">
                                <el-upload
                                    :headers="{ 'X-CSRF-TOKEN': csrf }"
                                    :action="link_cws+'/tasks/save-sliders-api'"
                                    list-type="picture-card"
                                    :on-success="handleSuccess"
                                    :file-list="form.task_galleries"
                                    :on-remove="handleRemove">
                                    <i class="el-icon-plus"></i>
                                </el-upload>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-form-item class="mt-5">
                        <el-button type="primary" @click="submitForm('form')">Save</el-button>
                        <el-button @click="drawerCreate = false">Cancel</el-button>
                    </el-form-item>
                </el-row>
            </el-form>
        </div>
        <!-- Sessions -->
        <el-drawer title="Sessions" size="50%" :visible.sync="dialogSessions" style="height: auto">
            <el-row :gutter="20" class="p-5">
                <el-col :span="18">
                    <div class="d-flex mb-2">
                        <el-input v-model="sessions.name" placeholder="Name"></el-input>
                        <el-input v-model="sessions.max_job" style="width: 40%; margin-left: 20px;" placeholder="Number success"></el-input>
                    </div>
                    <ckeditor v-model="sessions.description" ></ckeditor>
                </el-col>
                <el-col :span="6">
                    <el-upload
                        class="avatar-uploader text-center"
                        :headers="{ 'X-CSRF-TOKEN': csrf }"
                        :action="link_cws+'/tasks/save-avatar-api'"
                        :on-success="handleAvatarSuccess1"
                        :before-upload="beforeAvatarUpload">
                        <img v-if="sessions.banner_url" :src="sessions.banner_url" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-col>

                <!-- Add session -->
                <el-col :span="24">
                    <div v-for="(details, index) in  sessions.detail" class="mb-3 mt-3">
                        <el-row :gutter="20" >
                            <el-col :span="4"><div style="margin: 10px 0 0 0px">Session {{index+1}}</div></el-col>
                            <el-col :span="18" class="mb-2 d-flex">
                                <el-input v-model="details.name" placeholder="Name" style="width: 70%; margin-right: 10px;"></el-input>
                                <el-input v-model="details.description" style="margin-left: 20px;" placeholder="Description"></el-input>
                            </el-col>
                            <el-col :span="2">
                                <el-button size="mini" type="danger" style="margin-top: 5px" @click.prevent="removeSessions(details)"><i
                                    class="el-icon-delete"></i></el-button>
                            </el-col>
                        </el-row>
                    </div>
                    <div style="float: right;">
                        <el-button size="mini" type="success" @click="submitSession()" class="mt-3 mb-2">Done</el-button>
                        <el-button size="mini" type="primary" class="mt-3 mb-2" @click="addDetailSessions"><i class="el-icon-plus"></i>Add Detail</el-button>
                    </div>
                </el-col>
            </el-row>
        </el-drawer>
        <!-- Booths -->
        <el-drawer title="Booths" size="50%" :visible.sync="dialogBooths" style="height: auto">
            <el-row :gutter="20" class="p-5">
                <el-col :span="18">
                    <div class="d-flex mb-2">
                        <el-input v-model="booths.name" placeholder="Name"></el-input>
                        <el-input v-model="booths.max_job" style="width: 40%; margin-left: 20px;" placeholder="Sl Hoàn thành"></el-input>
                    </div>
                    <ckeditor v-model="booths.description" ></ckeditor>
                </el-col>
                <el-col :span="6">
                    <el-upload
                        class="avatar-uploader text-center"
                        :headers="{ 'X-CSRF-TOKEN': csrf }"
                        :action="link_cws+'/tasks/save-avatar-api'"
                        :on-success="handleAvatarSuccess2"
                        :before-upload="beforeAvatarUpload">
                        <img v-if="booths.banner_url" :src="booths.banner_url" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-col>
                <el-col :span="24">
                    <div v-for="(details, index) in  booths.detail" class="mb-3 mt-3">
                        <el-row :gutter="20" >
                            <el-col :span="4"><div style="margin: 10px 0 0 0px">Booth {{index+1}}</div></el-col>
                            <el-col :span="18" class="mb-2 d-flex" >
                                <el-input v-model="details.name" placeholder="Name" style="width: 70%"></el-input>
                                <el-input v-model="details.description" style="margin-left: 20px;" placeholder="Description"></el-input>
                            </el-col>
                            <el-col :span="2">
                                <el-button size="mini" type="danger" style="margin-top: 5px" @click.prevent="removeBooths(details)">
                                    <i class="el-icon-delete"></i>
                                </el-button>
                            </el-col>
                        </el-row>
                    </div>
                    <div style="float: right;">
                        <el-button size="mini" type="success" @click="submitBooth()" class="mt-3 mb-2">Done</el-button>
                        <el-button size="mini" type="primary" style="float: right" class="mt-3 mb-2" @click="addDetailBooths"><i class="el-icon-plus"></i>Add Detail</el-button>
                    </div>
                </el-col>
            </el-row>
        </el-drawer>
        <!--            quiz-->
        <el-drawer title="Quiz" size="50%" :visible.sync="dialogQuiz">
            <div class="p-3">
                <el-form ref="formQuiz"  label-position="top">
                    <div>
                        <el-col :span="24">
                            <div v-for="(details, index) in  this.quiz" class="mb-3 mt-3">
                                <el-row :gutter="20" >
                                    <div class="d-flex justify-content-between mb-2">
                                        <el-form-item :label="'Question '+ (index+1)" :label-width="formLabelWidth" style="width: 65%;">
                                            <el-input v-model="details.name" ></el-input>
                                        </el-form-item>
                                        <el-form-item label="Time" :label-width="formLabelWidth" style="width: 15%;">
                                            <el-input v-model="details.time_quiz" ></el-input>
                                        </el-form-item>
                                        <el-form-item label="Order" prop="order" :label-width="formLabelWidth" style="width: 5%;">
                                            <el-input v-model="details.order" ></el-input>
                                        </el-form-item>
                                        <el-form-item label="Status" prop="status" :label-width="formLabelWidth" style="width: 10%;">
                                            <el-switch v-model="details.status">
                                            </el-switch>
                                        </el-form-item>
                                    </div>
                                    <div v-for="(answer, index) in  details.detail"
                                         class="mb-3 mt-3 " style="margin-left: 40px;margin-right: 40px">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>Answer {{index+1}}</div>
                                            <el-input v-model="answer.name"  style="width: 80%;"></el-input>
                                            <el-checkbox @change="clickAnswer(answer,details.detail)" v-model="answer.status">Option</el-checkbox>
                                        </div>
                                    </div>
                                    <div><el-button size="mini" type="danger" style="margin-top: 5px" @click.prevent="removeQuiz(details)">
                                        <i class="el-icon-delete"></i>
                                    </el-button></div>
                                </el-row>
                            </div>
                            <div style="float: right;">
                                <el-button size="mini" type="success" class="mt-3 mb-2" @click="submitQuiz()"><i class="el-icon-plus"></i>Done</el-button>
                                <el-button size="mini" type="primary" @click="addQuiz()">Add Quiz</el-button>
                            </div>
                        </el-col>
                    </div>
                </el-form>
            </div>
        </el-drawer>
        <!--            social-->
        <el-drawer title="Social" size="50%" :visible.sync="dialogSocial" style="height: auto">
            <el-row :gutter="20" class="p-5">
                <el-col >
                    <el-card shadow="hover" class="box-card mb-2">
                        <div slot="header" class="clearfix">
                            <span>Social</span>
                        </div>
                        <div>
                            <el-col >
                                <el-checkbox  v-model="task_event_socials.is_comment">Comment</el-checkbox>
                                <el-checkbox v-model="task_event_socials.is_like">Like</el-checkbox>
                                <el-checkbox v-model="task_event_socials.is_retweet">Retweet</el-checkbox>
                                <el-checkbox v-model="task_event_socials.is_tweet">Tweet</el-checkbox>
                            </el-col>
                            <br>
                            <div v-if="task_event_socials.is_comment == true || task_event_socials.is_like == true || task_event_socials.is_retweet == true">
                                <span> URL</span>
                                <el-input class="mb-2 mt-2" maxlength="255" show-word-limit  placeholder="https://twitter.com/elonmusk/status/1638381090368012289" v-model="task_event_socials.url"></el-input>
                            </div>
                            <div v-if="task_event_socials.is_tweet == true">
                                <span> Text</span>
                                <el-input class="mb-2 mt-2" maxlength="255" show-word-limit  placeholder="Text ..." v-model="task_event_socials.text"></el-input>
                            </div>
                        </div>
                    </el-card>
                </el-col>
                <el-col >
                    <el-card shadow="hover" class="box-card mb-2">
                        <div slot="header" class="clearfix">
                            <span>Discord</span>
                        </div>
                        <div class="row">
                            <div>
                                <span> Bot Token</span>
                                <el-input class="mb-2 mt-2"   placeholder=" Bot Token" v-model="task_event_discords.bot_token"></el-input>
                            </div>
                            <div class="col-md-6">
                                <span> Channel Id</span>
                                <el-input class="mb-2 mt-2"   placeholder="Channel Id" v-model="task_event_discords.channel_id"></el-input>
                            </div>
                            <div class="col-md-6">
                                <span> Channel Url</span>
                                <el-input class="mb-2 mt-2"   placeholder="Channel Url" v-model="task_event_discords.channel_url"></el-input>
                            </div>
                        </div>
                    </el-card>
                </el-col>
                <div style="float: right;">
                    <el-button size="mini" type="success" @click="submitSocial()" class="mt-3 mb-2">Done</el-button>
                </div>
            </el-row>
        </el-drawer>
    </div>
</template>

<script>
import { ref } from 'vue'
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
export default {
    name: "edit",
    props: ['task_id','link_cws','csrf','type_reward'],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            typeReward:[

            ],
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() < Date.now();
                },
            },
            rules: {
                name: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                lat: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                lng: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                start_at: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                order: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                end_at: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                address: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                description: [{
                    required: true,
                    message: 'Please input description',
                    trigger: ['blur', 'change']
                }]
            },
            ruleQuiz:{
                name: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],
                time_quiz: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }],

            },
            formLabelWidth: '120px',
            dialogSessions: false,
            dialogQuiz: false,
            dialogBooths: false,
            dialogSocial: false,
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
                is_paid : false,
                reward : 0,
                reward_type : 1,
                task_galleries: [],
            },
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
            sessions: {
                name:'',
                max_job:'',
                banner_url:'',
                description:'',
                type:0,
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
        }
    },
    methods: {
        clickAnswer(item,items){
            for (let i = 0; i < items.length; i++) {
                const el = items[i];
                if (el.key === item.key) {
                    el.status = true;
                } else {
                    el.status = false;
                }
            }
        },
        addQuiz(){
            this.quiz.push({
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
            });
        },
        submitQuiz(){
            this.form.quiz = this.quiz
            this.dialogQuiz = false
        },
        submitSession(){
            this.form.sessions = this.sessions
            this.dialogSessions = false
        },
        submitBooth(){
            this.form.booths = this.booths
            this.dialogBooths = false
        },
        submitSocial(){
            this.form.task_event_socials = this.task_event_socials
            this.form.task_event_discords = this.task_event_discords
            this.dialogSocial = false
        },
        removeQuiz(item){
            let index = this.quiz.indexOf(item);
            if (index !== -1) {
                this.quiz.splice(index, 1);
            }
        },
        removeSessions(item){
            let index = this.sessions.detail.indexOf(item);
            if (index !== -1) {
                this.sessions.detail.splice(index, 1);
            }
        },
        removeBooths(item){
            let index = this.booths.detail.indexOf(item);
            if (index !== -1) {
                this.booths.detail.splice(index, 1);
            }
        },
        addDetailSessions(){
            this.sessions.detail.push({
                name: '',
                description: '',
            });
        },
        addDetailBooths(){
            this.booths.detail.push({
                name: '',
                description: '',
            });
        },
        addSessions(){
            this.dialogSessions = true
        },
        addBooths(){
            this.dialogBooths = true
        },
        addSocial(){
            this.dialogSocial = true
        },
        handleRemove(file, fileList) {
            this.form.task_galleries = fileList
        },
        handlePreview(file) {
            console.log(file);
        },
        handleSuccess( file,fileList) {
            this.form.task_galleries.push(
                file
            )

        },
        handleAvatarSuccess(res, file) {
            this.form.banner_url = URL.createObjectURL(file.raw);
            this.form.banner_url = res;
        },
        handleAvatarSuccess1(res, file) {
            this.sessions.banner_url = URL.createObjectURL(file.raw);
            this.sessions.banner_url = res;
        },
        handleAvatarSuccess2(res, file) {
            this.booths.banner_url = URL.createObjectURL(file.raw);
            this.booths.banner_url = res;
        },
        beforeAvatarUpload(file) {
            const isJPEG = file.type === 'image/jpeg';
            const isPNG = file.type === 'image/png';
            const isJPG = file.type === 'image/jpg';

            const isLt10M = file.size / 1024 / 1024 < 10;

            if (!isJPEG && !isPNG && !isJPG) {
                this.$message.error('Chọn ảnh định dạng JPG,PNG,JPEG!');
                return false
            }
            if (!isLt10M) {
                this.$message.error('Avatar picture size can not exceed 2MB!')
                return false
            }
            return isLt10M;
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
                    if (this.form.task_event_socials.is_tweet == false){
                        this.form.task_event_socials.text = null
                    }
                    if (this.form.task_event_socials.is_like == false && this.form.task_event_socials.is_comment == false && this.form.task_event_socials.is_retweet == false ){
                        this.form.task_event_socials.url = null
                    }
                    axios.post(this.link_cws+'/api/events', this.form).then(e => {
                        Notification.success({
                            title: ' Thành công',
                            message: ' Thành công',
                            type: 'success',
                        });
                        loading.close();
                        window.location.href = '/events';
                    }).catch(error => {
                        this.errors = error.response.data.message; // this should be errors.
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
                if (e.data.data.message.sessions != null){
                    this.sessions = e.data.data.message.sessions
                }else {
                    this.sessions = {
                        name:'',
                        max_job:'',
                        banner_url:'',
                        description:'',
                        type:0,
                        detail:[
                            {
                                name: '',
                                description: ''
                            }
                        ]
                    }
                }
                if (e.data.data.message.task_event_socials != null){
                    this.task_event_socials = e.data.data.message.task_event_socials
                }else {
                    this.task_event_socials = {
                        url:'',
                        text:'',
                        is_comment:false,
                        is_like:false,
                        is_retweet:false,
                        is_tweet:false,
                        type:0,
                    }
                }
                if (e.data.data.message.booths != null){
                    this.booths = e.data.data.message.booths
                }else {
                    this.booths = {
                        name:'',
                        max_job:'',
                        banner_url:'',
                        description:'',
                        type:1,
                        detail:[
                            {
                                name: '',
                                description: ''
                            }
                        ]
                    }
                }
                if (e.data.data.message.task_event_socials == null){
                    this.form.task_event_socials = {
                        url:'',
                        text:'',
                        is_comment:false,
                        is_like:false,
                        is_retweet:false,
                        is_tweet:false,
                        type:0,
                    }
                }
                if (e.data.data.message.task_event_discords != null){
                    this.task_event_discords = e.data.data.message.task_event_discords
                }else {
                    this.task_event_discords = {
                        bot_token:'',
                        channel_id:'',
                        channel_url:'',
                    }
                }
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
    },
    mounted: function () {
        this.typeReward = JSON.parse(this.type_reward)
        this.listData()
    },
}
</script>

<style scoped>

</style>

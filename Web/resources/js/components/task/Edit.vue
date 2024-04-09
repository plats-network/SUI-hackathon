<template>
    <div>
        <div style="display: flex;justify-content: space-between">
            <h4>Edit task</h4>
            <h3><a href="/tasks" ><el-button type="primary" icon="el-icon-back"></el-button></a>
            </h3>
        </div>
        <el-form ref="form" class="form-style" label-position="top" :model="form" label-width="120px">
            <el-row :gutter="20">
                <el-col :span="16">
                    <el-form-item label="Name">
                        <el-input v-model="form.name" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Description" prop="Description">
                        <el-input type="textarea" placeholder="Description" v-model="form.description"></el-input>
                    </el-form-item>
                    <el-form-item label="Group" prop="region">
                        <el-select class="w-100" v-model="form.group_tasks" multiple="" placeholder="Group">
                            <el-option
                                v-for="item in dataGroups"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Option">
                        <el-col :span="6" >
                            <el-input v-model="form.order" placeholder="Order" style="padding-left: 0;"></el-input>
                        </el-col>
                        <el-col :span="6" style="padding-right: 10px;">
                            <el-date-picker type="datetime" placeholder="Start at" v-model="form.start_at"></el-date-picker>
                        </el-col>
                        <el-col class="text-center" :span="4">-</el-col>
                        <el-col :span="6">
                            <el-date-picker type="datetime" placeholder="End at" v-model="form.end_at"></el-date-picker>
                        </el-col>
                    </el-form-item>
                    <div class="d-flex mt-4">
                        <el-button type="primary"  @click="dialogCheckIn = true">
                            CheckIn
                        </el-button>
                        <el-button  type="success" @click="dialogSocial = true">
                            Social
                        </el-button>
                    </div>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Image">
                        <el-upload
                            class="avatar-uploader text-center"
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            action="/tasks/save-avatar-api"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                            <img v-if="form.banner_url" :src="form.banner_url" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Slider">
                        <el-upload
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            action="/tasks/save-sliders-api"
                            list-type="picture-card"
                            :on-success="handleSuccess"
                            :file-list="form.task_galleries"
                            :on-remove="handleRemove">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-dialog title="Check In" :visible.sync="dialogCheckIn" style="height: auto">
                <div v-for="(location, index) in form.task_locations" class="mb-3"
                     style="border-radius: 5px;border: 1px solid #DCDFE6;padding: 10px">
                    <el-row :gutter="20">
                        <el-col :span="10">
                            <el-input v-model="location.name" placeholder="Name"></el-input>
                            <div style="margin-top: 15px;display: flex">
                                <el-select v-model="location.reward_id" placeholder="Reward">
                                    <el-option
                                        v-for="item in dataReward"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                                <el-input placeholder="Amount" v-model="location.amount"
                                          class="input-with-select"></el-input>
                            </div>
                        </el-col>
                        <el-col :span="10">
                            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 8}" placeholder="Description"
                                      v-model="location.description"></el-input>
                        </el-col>
                        <el-col :span="2">
                            <el-button size="mini" type="danger" @click.prevent="removeLocations(location)"><i
                                class="el-icon-delete"></i></el-button>
                        </el-col>
                        <el-col :span="22" class="mb-2" style="margin-left: 30px">
                            <div v-for="(detail, index) in location.task_location_jobs" class="mb-2 mt-2"
                                 style="border-radius: 5px;border: 1px solid #DCDFE6;padding: 10px">
                                <el-row :gutter="20">
                                    <el-col :span="20">
                                        <div class="d-flex justify-content-between mb-2">
                                            <el-input v-model="detail.name" placeholder="Name"></el-input>
                                            <el-input v-model="detail.address" placeholder="Address"></el-input>
                                        </div>
                                    </el-col>
                                    <el-col :span="2">
                                        <el-button size="mini" type="danger"
                                                   @click.prevent="removeLocationsDetail(detail,location.task_location_jobs)"><i
                                            class="el-icon-delete"></i></el-button>
                                    </el-col>
                                </el-row>
                                <div class="d-flex justify-content-between">
                                    <el-input v-model="detail.order" placeholder="Order"></el-input>
                                    <el-input v-model="detail.lng" placeholder="Long"></el-input>
                                    <el-input v-model="detail.lat" placeholder="Lat"></el-input>
                                </div>
                            </div>
                            <el-button size="mini" type="primary" class="mb-2 mt-2"
                                       @click="addLocationsDetails(location.task_location_jobs)"><i class="el-icon-plus"></i> Add Job
                            </el-button>
                        </el-col>
                    </el-row>
                </div>
                <el-button size="mini" type="primary" class="mt-3 mb-2" @click="addLocations"><i class="el-icon-plus"></i>Add
                    Check In
                </el-button>
            </el-dialog>
            <el-dialog title="Social" :visible.sync="dialogSocial" style="height: auto">
                <div v-for="(social, index) in  form.task_socials" class="mb-3"
                     style="border-radius: 5px;border: 1px solid #DCDFE6;padding: 10px">
                    <el-row :gutter="20">
                        <el-col :span="12" class="mb-1">
                            <el-select v-model="social.platform" class="w-100" placeholder="Platform">
                                <el-option
                                    v-for="item in platformSocial"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="12" class="mb-1">
                            <el-select v-model="social.type" placeholder="Type" class="w-100">
                                <el-option v-if="social.platform == 1"
                                           v-for="item in typeSocialFacebook"
                                           :key="item.value"
                                           :label="item.label"
                                           :value="item.value">
                                </el-option>
                                <el-option v-if="social.platform == 2"
                                           v-for="item in typeSocialTwitter"
                                           :key="item.value"
                                           :label="item.label"
                                           :value="item.value">
                                </el-option>
                                <el-option v-if="social.platform == 3"
                                           v-for="item in typeSocialTelegram"
                                           :key="item.value"
                                           :label="item.label"
                                           :value="item.value">
                                </el-option>
                                <el-option v-if="social.platform == 4"
                                           v-for="item in typeSocialDiscord"
                                           :key="item.value"
                                           :label="item.label"
                                           :value="item.value">
                                </el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="12" class="mb-1">
                            <el-input v-model="social.name" placeholder="Name"></el-input>
                        </el-col>
                        <el-col :span="12" class="mb-1">
                            <el-select v-model="social.reward_id" class="w-100" placeholder="Reward">
                                <el-option
                                    v-for="item in dataReward"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                                </el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="12" class="mb-1">
                            <el-input v-model="social.amount" placeholder="Total Reward"></el-input>
                        </el-col>
                        <el-col :span="20">
                            <el-input v-model="social.url" placeholder="Url"></el-input>
                        </el-col>
                        <el-col :span="2">
                            <el-button size="mini" type="danger" @click.prevent="removeSocial(social)"><i
                                class="el-icon-delete"></i></el-button>
                        </el-col>
                    </el-row>
                </div>
                <el-button size="mini" type="primary" class="mt-3 mb-2" @click="addSocial"><i class="el-icon-plus"></i>Add
                    Social
                </el-button>
            </el-dialog>
            <el-form-item class="mt-5">
                <el-button type="primary" @click="submitForm('form')">Edit</el-button>
                <el-button>Cancel</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import 'element-tiptap/lib/index.css';
import {Notification} from 'element-ui';
import {ElementTiptap} from 'element-tiptap';
import {
    // necessary extensions
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
    name: "Edit",
    props: ['csrf','id'],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            dialogVisible: false,
            dialogCheckIn: false,
            dialogSocial: false,
            disabled: false,
            dataGroups : [
                {
                    id : '',
                    name : ''
                }
            ],
            dataReward:[
                {

                }
            ],
            platformSocial : [
                {
                    value: 1,
                    label: 'Facebook'
                },
                {
                    value: 2,
                    label: 'Twitter'
                },
                {
                    value: 3,
                    label: 'Telegram'
                },
                {
                    value: 4,
                    label: 'Discord'
                }
            ],
            typeSocialFacebook : [
                {
                    value: 1,
                    label: 'Like'
                },
                {
                    value: 2,
                    label: 'Share'
                },
                {
                    value: 3,
                    label: 'Post có task bạn bè'
                },
                {
                    value: 4,
                    label: 'Post có hashtag theo từ khóa'
                },
                {
                    value: 5,
                    label: 'Comment bài viết'
                },
            ],
            typeSocialTwitter : [
                {
                    value: 1,
                    label: 'like'
                },
                {
                    value: 2,
                    label: 'Retweet'
                },
                {
                    value: 3,
                    label: 'Tags bạn bè trong bài post'
                },
                {
                    value: 4,
                    label: 'Hashtag'
                },
                {
                    value: 5,
                    label: 'Follow'
                },
            ],
            typeSocialTelegram : [
                {
                    value: 1,
                    label: 'Join telegram'
                },
            ],
            typeSocialDiscord : [
                {
                    value: 1,
                    label: 'Join telegram'
                },
            ],
            form: {
                id : '',
                name : '',
                description : '',
                group_tasks : [
                    {
                        id : '',
                        name : ''
                    }
                ],
                status : '',
                order : '',
                image : '',
                start_at : '',
                end_at : '',
                banner_url : '',
                task_galleries: [

                ],
                task_locations: [
                    {
                        name: '',
                        reward_id: '',
                        description: '',
                        amount: '',
                        order: 0,
                        task_location_jobs: [
                            {
                                name: '',
                                address: '',
                                order: '',
                                lng: '',
                                lat: '',
                            }
                        ]
                    }
                ],
                task_socials: [
                    {
                        type: '',
                        url: '',
                        platform: '',
                        reward_id: '',
                        amount: '',
                    }
                ]
            },
        }
    },
    methods: {
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
        submitForm(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    axios.post('/api/tasks-cws/store', this.form).then(e => {
                        Notification.success({
                            title: ' Thành công',
                            message: ' Thành công',
                            type: 'success',
                        });
                        loading.close();
                        window.location.href = '/tasks';
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
        socialFilter(platform) {
            return this.form.task_socials.filter(i => i.platform == platform)
        },
        addLocations() {
            this.form.task_locations.push({
                name: '',
                reward_id: '',
                description: '',
                amount: '',
                order: 1,
                task_location_jobs: [
                    {
                        name: '',
                        address: '',
                        order: '',
                        lng: '',
                        lat: '',
                    }
                ]
            });
        },
        addSocial() {
            this.form.task_socials.push({
                type: '',
                url: '',
                platform: '',
                reward_id: '',
                amount: '',
            });
        },
        removeSocial(item) {
            let index = this.form.task_socials.indexOf(item);
            if (index !== -1) {
                this.form.task_socials.splice(index, 1);
            }
        },
        addLocationsDetails(detail) {
            detail.push({
                name: '',
                address: '',
                sort: '',
                lng: '',
                lat: '',

            });
        },
        removeLocations(item) {
            let index = this.form.task_locations.indexOf(item);
            if (index !== -1) {
                this.form.task_locations.splice(index, 1);
            }
        },
        removeLocationsDetail(item, locations) {
            var index = locations.indexOf(item);
            if (index !== -1) {
                locations.splice(index, 1);
            }
        },
        handleAvatarSuccess(res, file) {
            this.form.banner_url = URL.createObjectURL(file.raw);
            this.form.banner_url = res;
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
        listGroups(val = 1) {
            let rawData =
                {
                    'limit': 1000,
                }
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/api/groups'
            axios.get(url, {
                params: rawData
            }).then(e => {
                this.dataGroups = e.data.data;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        listRewards(val = 1) {
            let rawData =
                {
                    'limit': 1000,
                }
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/api/rewards'
            axios.get(url, {
                params: rawData
            }).then(e => {
                this.dataReward = e.data.data.data;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        getDetail(){
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/api/tasks-cws/edit/'+ this.id;
            axios.get(url).then(e => {
                console.log(e.data.data.message)
                this.form = e.data.data.message;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        }
    },
    mounted: function () {
        this.listGroups()
        this.listRewards()
        this.getDetail()
    },
}
</script>

<style scoped>

</style>

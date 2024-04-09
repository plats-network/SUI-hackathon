<template>
    <div class="container">
        <span class="title-like">LIKES</span>
        <div class="container">
            <div class="mt-3" style="font-weight: 800;font-size: 20px;color: #545454;">Events</div>
            <el-row>
                <el-col :md="6" :sm="12" :xs="12" :lg="6" :xl="6" class="p-2" v-for="event in tableData"  >
                    <el-card shadow="hover" class="height-card" :body-style="{ padding: '5px' }">
                        <img :src="event.banner_url" class="image w-100 height-card-image">
                        <div>
                            <button
                                :class="{ active: selected_options.includes(event.id)}"
                                @click="toggle_selection_for(event.id)"
                                class="btn btn-outline-primary" style="padding: 10px 10px;border-radius: 20px;position: relative;top:-16px;float: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path>
                                </svg>
                            </button>
                        </div>
                        <div style="padding: 22px 5px;">
                            <span  style="font-weight: 800;font-size: 16px"><a  v-bind:href="'/events/'+ event.slug" style="color:black;">{{event.name}}</a></span>
                            <div class="">
                                <span class="d-block mb-1 mt-1" type="text" ><i class="el-icon-time"></i> {{event.end_at | moment}}</span>
                                <span class="d-block" v-html="">{{event.description | truncate(50)}}</span>
                            </div>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
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
    name: "likes",
    data() {
        return {
            currentDate: new Date(),
            totalNumber:0,
            totalItem:0,
            tableData:[],
            selected_options: [],
        }
    },
    methods:{
        toggle_selection_for(option) {
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let rawData =
                {
                    'task_id': option,
                }
            axios.post('/events/likes', rawData).then(e => {
                Notification.success({
                    title: ' Thành công',
                    message: ' Thành công',
                    type: 'success',
                });
                loading.close();
            }).catch(error => {
                this.errors = error.response.data.message; // this should be errors.
                Notification.error({
                    title: 'Error',
                    message: this.errors,
                    type: 'error',
                });
                loading.close();
            });
            if (this.selected_options.includes(option)) {
                this.selected_options = this.selected_options.filter(
                    (item) => item !== option
                );
            } else {
                this.selected_options.push(option);
            }
        },
        list_data(val = 1, type = true) {
            var self = this;
            let rawData =
                {
                }
            if (!type) {
                rawData['size'] = val;
            } else {
                this.page = val;
                rawData['page'] = val;
            }
            rawData['page'] = val;
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/events/likes/list'
            axios.get(url, {
                params: rawData
            }).then(e => {
                this.tableData = e.data.data;
                this.tableData.forEach((element) => {
                    this.selected_options.push(element.id);
                });
                this.totalNumber = e.data.meta.total;
                self.totalItem = e.data.meta.total;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
    },
    mounted: function () {
        this.list_data()
        this.isHidden = this.active
    },
    filters: {
        moment: function (date) {
            return moment(date).format(' YYYY-MM-DD HH:mm:ss ');
        },
    }
}
</script>

<style scoped>
    @media (max-width: 480px) {
        .pa-like{
            padding: 0 !important;
        }
    }


    @media (max-width: 992px) {
        .pa-like{
            padding: 0 !important;
        }

    }
    .pa-like{
        padding: 30px 100px;
    }
    .title-item-like{
        font-size: 20px;
        color: #545454;
        font-weight: 800;
    }
    .title-like{
        font-size: 45px;
        color: #545454;
        font-weight: 800;
    }
</style>

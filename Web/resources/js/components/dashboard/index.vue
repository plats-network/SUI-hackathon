<template>
    <el-row>
        <el-row class="mb-1">
            <el-col :span="6" v-for="(o, index) in data" class="p-1">
                <el-card :body-style="{ padding: '0px' }">
                    <a  v-bind:href="''+o.link" style="color:black;">
                    <div style="padding: 14px;">
                        <div slot="header" class="clearfix">
                            <img :src="o.image" class="image w-100 height-card-image">
                        </div>
                        <h3 class="mt-2">{{o.name}}</h3>
                    </div>
                    </a>
                </el-card>
            </el-col>
        </el-row>

    </el-row>
</template>

<script>
import { ref } from 'vue'
import 'element-tiptap/lib/index.css';
import {Notification} from 'element-ui';
import {ElementTiptap} from 'element-tiptap';
import QrcodeVue from 'qrcode.vue'
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
    name: "index",
    props: [],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            currentDate: new Date(),
            data:[
                {
                    name : 'Task',
                    link : '/tasks',
                    image : 'https://www.studytienganh.vn/upload/2021/06/105234.jpg'
                },
                {
                    name : 'Event',
                    link : '/events',
                    image : 'https://blog.freelancerviet.vn/wp-content/uploads/2019/03/ZEeC1_events_medium.jpg'
                },

            ]
        }
    },
    methods: {

    },
    mounted: function () {

    }
}
</script>

<style scoped>

</style>

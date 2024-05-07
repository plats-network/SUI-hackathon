// tạo thông báo hiển thị thời gian chờ
import React, { useEffect, useState } from 'react';

const TimerComponent = ({ onClick, intervalTime }) => {
    const [lastClickTime, setLastClickTime] = useState(null);

    useEffect(() => {
        const interval = setInterval(() => {
            // Kiểm tra nếu đã trôi qua intervalTime từ lần click cuối cùng
            if (lastClickTime && (Date.now() - lastClickTime) >= intervalTime) {
                clearInterval(interval); // Dừng interval
                setLastClickTime(null); // Reset thời gian click
            }
        }, 1000); // Kiểm tra mỗi giây

        // Xóa interval khi component unmount
        return () => clearInterval(interval);
    }, [lastClickTime, intervalTime]);

    const handleClick = () => {
        setLastClickTime(Date.now()); // Thiết lập thời gian click mới nhất
        onClick(); // Gọi hàm onClick được truyền từ component cha
    };

    return null; // Không render gì ra ngoài, chỉ xử lý thời gian
};

export default TimerComponent;
document.querySelectorAll('.fi-sidebar-item-active').forEach(item => {
    const div = document.createElement('div');
    div.innerHTML = item.innerHTML;
    item.innerHTML = '<div style="margin-left: -1px; width: 7px; height: 50px; background: blue;  border-top-right-radius: 20px; border-bottom-right-radius: 20px;"></div>';
    item.appendChild(div);
});

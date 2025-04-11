document.addEventListener('DOMContentLoaded', () => {
    const productGrid = document.getElementById('product-grid');
    const paginationContainer = document.getElementById('pagination');
    const resultsCount = document.getElementById('results-count');
    const sortSelect = document.getElementById('sort');

    const API_URL = 'api/products.php';
    const ITEMS_PER_PAGE = 12;

    let currentPaginationData = null;

    async function fetchProducts(page) {
        productGrid.innerHTML = '<p>Đang tải sản phẩm...</p>';
        paginationContainer.innerHTML = '';
        resultsCount.textContent = 'Đang tải...';
        console.log(`Fetching page: ${page}`); 

        try {
            const response = await fetch(`${API_URL}?page=${page}&limit=${ITEMS_PER_PAGE}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('API Response:', data); 

            currentPaginationData = data.pagination;

            displayProducts(data.products);
            displayPagination(currentPaginationData); 
            updateResultsCount(currentPaginationData);

        } catch (error) {
            console.error("Lỗi khi tải sản phẩm:", error);
            productGrid.innerHTML = '<p>Không thể tải sản phẩm. Vui lòng thử lại sau.</p>';
            resultsCount.textContent = 'Lỗi tải dữ liệu';
            currentPaginationData = null; 
        }
    }

    function displayProducts(products) {
        productGrid.innerHTML = '';
        if (products.length === 0) {
            productGrid.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
            return;
        }
        products.forEach(product => {
            const card = document.createElement('div');
            card.classList.add('product-card');
            const imageUrl = product.imageUrl ? product.imageUrl : 'https://via.placeholder.com/300x250/f4f5f7/ccc?text=No+Image';
            card.innerHTML = `
                <img src="${imageUrl}" alt="${product.name}" class="product-image" loading="lazy">
                <div class="product-info">
                    <h3 class="product-name">${product.name}</h3>
                    ${product.category ? `<p class="product-category">${product.category}</p>` : ''}
                    <p class="product-price">
                        ${product.formatted_price}
                        ${product.formatted_old_price ? `<span class="product-old-price">${product.formatted_old_price}</span>` : ''}
                    </p>
                </div>
            `;
            productGrid.appendChild(card);
        });
    }

    function updateResultsCount(pagination) {
         if (!pagination || pagination.totalItems === 0) {
             resultsCount.textContent = 'Không có sản phẩm nào';
         } else {
            resultsCount.textContent = `Hiển thị ${pagination.itemFrom}–${pagination.itemTo} của ${pagination.totalItems} kết quả`;
        }
    }


    function displayPagination(pagination) {
        paginationContainer.innerHTML = ''; 
        console.log('Displaying pagination for:', pagination);

        if (!pagination) return;

        const { currentPage, totalPages } = pagination;

        if (totalPages <= 1) return;

        const prevButton = createPaginationButton('Prev', currentPage - 1, pagination);
        if (currentPage === 1) {
            prevButton.classList.add('disabled');
            prevButton.style.pointerEvents = 'none';
            prevButton.setAttribute('aria-disabled', 'true');
        }
        paginationContainer.appendChild(prevButton);

        const maxPagesToShow = 5;
        let startPage, endPage;

        if (totalPages <= maxPagesToShow) {
            startPage = 1;
            endPage = totalPages;
        } else {
            const maxPagesBeforeCurrent = Math.floor((maxPagesToShow - 1) / 2);
            const maxPagesAfterCurrent = Math.ceil((maxPagesToShow - 1) / 2);

            if (currentPage <= maxPagesBeforeCurrent + 1) {
                startPage = 1;
                endPage = maxPagesToShow;
            } else if (currentPage + maxPagesAfterCurrent >= totalPages) {
                startPage = totalPages - maxPagesToShow + 1;
                endPage = totalPages;
            } else {
                startPage = currentPage - maxPagesBeforeCurrent;
                endPage = currentPage + maxPagesAfterCurrent;
            }
        }

        if (startPage > 1) {
            paginationContainer.appendChild(createPaginationButton(1, 1, pagination));
            if (startPage > 2) {
                 const dots = document.createElement('span');
                 dots.classList.add('dots');
                 dots.textContent = '...';
                 paginationContainer.appendChild(dots);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                const currentSpan = document.createElement('span');
                currentSpan.classList.add('current-page');
                currentSpan.textContent = i;
                paginationContainer.appendChild(currentSpan);
            } else {
                paginationContainer.appendChild(createPaginationButton(i, i, pagination));
            }
        }

        if (endPage < totalPages) {
             if (endPage < totalPages - 1) {
                 const dots = document.createElement('span');
                 dots.classList.add('dots');
                 dots.textContent = '...';
                 paginationContainer.appendChild(dots);
            }
            paginationContainer.appendChild(createPaginationButton(totalPages, totalPages, pagination));
        }

        const nextButton = createPaginationButton('Next', currentPage + 1, pagination);
        if (currentPage === totalPages) {
            nextButton.classList.add('disabled');
            nextButton.style.pointerEvents = 'none'; 
            nextButton.setAttribute('aria-disabled', 'true');
        }
        paginationContainer.appendChild(nextButton);
    }

    function createPaginationButton(text, pageNumber, paginationData) {
        const element = document.createElement('a');
        element.textContent = text;
        element.href = '#'; 

        const { currentPage, totalPages } = paginationData;

        let isClickable = false;
        if (text === 'Prev' && currentPage > 1) {
            isClickable = true;
        } else if (text === 'Next' && currentPage < totalPages) {
            isClickable = true;
        } else if (!isNaN(pageNumber) && pageNumber !== currentPage && pageNumber >= 1 && pageNumber <= totalPages) {
            isClickable = true;
        }

        if (isClickable) {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                console.log(`Clicked: ${text}, Target Page: ${pageNumber}`);
                fetchProducts(pageNumber); 
            });
        } else {
             element.setAttribute('aria-disabled', 'true');
        }

        return element;
    }

    fetchProducts(1);

});
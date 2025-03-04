<?xml version="1.0" encoding="UTF-8"?>
<!-- Author name: Lim Shuye -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="main-content">
            <h2>Purchase Order</h2>
                
            <table class="displayingTable">
                <tr>
                    <th>Order #</th>
                    <th>Supplier</th>
                    <th>Created At</th>
                    <th>Delivery Date</th>
                    <th>Billing Address</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                <xsl:for-each select="/root/purchaseorder">
                    <xsl:sort select="orderDate" order="descending"/>
                    <tr>
                        <td>
                            <xsl:value-of select="poId" />
                        </td>
                        <td>
                            
                            <xsl:value-of select="/root/supplier[supplierId = current()/supplierId]/supplierName" />

                        </td>
                        <td>
                            <xsl:value-of select="orderDate" />
                        </td>
                        <td>
                            <xsl:value-of select="deliveryDate" />
                        </td>
                        <td>
                            <xsl:value-of select="billingAddress" />
                        </td>
                        <td>
                            <xsl:value-of select="totalAmount" />
                        </td>
                        <td> 
                            <a class="hrefText poStatus">
                                <xsl:attribute name="href">
                                    <xsl:value-of select="concat('?controller=inventory&amp;action=sendPO&amp;POid=', poId)" />
                                </xsl:attribute>
                                <xsl:choose>
                                    <xsl:when test="status = 'Draft'">
                                        <div class="poStatus draft">Draft</div>
                                    </xsl:when>
                                    <xsl:when test="status = 'Pending'">
                                        <div class="poStatus processing">Processing</div>
                                    </xsl:when>
                                    <xsl:when test="status = 'Completed'">
                                        <div class="poStatus received">Completed</div>
                                    </xsl:when>
                                </xsl:choose>
                            </a>
                        </td>
                    </tr>
                </xsl:for-each>
            </table>
        </div>
    </xsl:template>
</xsl:stylesheet>

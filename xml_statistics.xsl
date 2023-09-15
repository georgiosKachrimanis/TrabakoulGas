<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">

        <html>
            <head>
                <meta charset="UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1"/>
                <title>Ενεργές Προσφορές</title>
                <link rel="stylesheet" href="css/style_trabakoulgas.css"/>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
                      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
            </head>
            <header>
                <!-- Navigation bar -->
                <nav class="nav justify-content-center flex-wrap" aria-label="Main Navigation">
                    <!-- Navigation bar items -->
                    <div class="row">
                        <div class="col">
                            <a href="index.php" aria-label="TrabakoulGas"><img
                                    src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"/></a>
                        </div>
                        <div class="col">
                            <a class="nav-link" href="index.php">Αρχική</a>
                        </div>

                        <!-- Active page: Offers -->
                        <div class="col">
                            <a class="nav-link active disabled" href="xml_statistics.php">Προσφορές</a>
                        </div>
                        <!-- Navigation link: Search -->
                        <div class="col">
                            <a class="nav-link" href="search.php">Αναζήτηση</a>
                        </div>
                        <!-- Navigation link: Announcements -->
                        <div class="col">
                            <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
                        </div>
                        <div class="col">
                            <!-- This column contains the download button, which is a styled button element inside a link element. -->
                            <a class="nav-link" href="download.php?tax_id={/company/tax_id}">
                                <button type="button" class="button1">Download XML</button>
                            </a>
                        </div>
                        <div class="col">
                        <!-- This column contains the logout button, which is a styled button element inside a link element. -->
                        <a class="nav-link" href="logout.php">
                            <button type="button" class="button1">Logout</button>
                        </a>
                        </div>

                    </div>
                </nav>
            </header>
            <body>
                <div class="xml">
                    <h2>Προσφορές</h2>
                    <table border="1">
                        <tr bgcolor="#9acd32">
                            <th>Fuel Name</th>
                            <th>Minimum Price</th>
                            <th>Maximum Price</th>
                            <th>Average Price</th>
                        </tr>
                        <xsl:for-each select="company/global_offers/global_offer">
                            <tr>
                                <td><xsl:value-of select="fuel_name"/></td>
                                <td><xsl:value-of select="minPrice"/></td>
                                <td><xsl:value-of select="maxPrice"/></td>
                                <td><xsl:value-of select="avgPrice"/></td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
                <div class="xml" >
                    <h2>Πληροφορίες Επιχείρησης</h2>
                    <table border="1" class="xml_table">
                        <tr>
                            <th>Όνομα</th>
                            <th>Α.Φ.Μ</th>
                            <th>Οδός</th>
                            <th>Αριθμός</th>
                            <th>Ταχ. Κώδικας</th>
                            <th>Περιφέρεια</th>
                            <th>Πόλη</th>
                        </tr>
                        <tr>
                            <td><xsl:value-of select="company/company_name"/></td>
                            <td><xsl:value-of select="company/tax_id"/></td>
                            <td><xsl:value-of select="company/location/street"/></td>
                            <td><xsl:value-of select="company/location/street_number"/></td>
                            <td><xsl:value-of select="company/location/postal_code"/></td>
                            <td><xsl:value-of select="company/location/region"/></td>
                            <td><xsl:value-of select="company/location/city"/></td>
                        </tr>
                        <tr>
                            <th colspan = "3">Σύνολο Προσφορών</th>
                            <th colspan = "2">Σύνολο ενεργών</th>
                            <th colspan = "2">Σύνολο ανενεργών</th>
                        </tr>
                        <tr>
                            <td colspan = "3"><xsl:value-of select="count(company/offers/offer)"/></td>
                            <td colspan = "2"><xsl:value-of select="count(company/offers/offer[@active='true'])"/></td>
                            <td colspan = "2"><xsl:value-of select="count(company/offers/offer[@active='false'])"/></td>
                        </tr>
                    </table>
                </div>
                <div class="xml" >

                    <h2>Ενεργές Προσφορές</h2>
                    <table border="1" class="xml_table">
                        <tr bgcolor="yellow" style="margin-left = 2%">
                            <th>Τύπος Καυσίμου</th>
                            <th>Λήξη Προσφοράς</th>
                            <th>Τιμή</th>
                        </tr>
                        <xsl:for-each select="company/offers/offer">
                            <tr>
                                <td><xsl:value-of select="fuel_type"/></td>
                                <td><xsl:value-of select="end_date"/></td>
                                <td><xsl:value-of select="price"/></td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>

            </body>

            <footer>
                <!-- Privacy and policy Links-->
                <!-- Copyright -->
                <div class="text-lg-center footer">
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <a class="nav-link" href="terms_of_use.html"> Όροι χρήσης</a>
                            <a class="nav-link" href="policy.html">Πολιτική Απορρήτου</a>
                            Copyright: © 2022
                            <a class="text-reset fw-bold" href="index.php">TrabakoulGas.com</a>
                        </div>
                        <div class="col-4"></div>
                    </div>
                </div>
            </footer>
        </html>
    </xsl:template>

</xsl:stylesheet>

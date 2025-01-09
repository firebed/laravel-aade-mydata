<?php

namespace Firebed\LaravelAadeMyData;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestE3Info;
use Firebed\AadeMyData\Http\RequestMyExpenses;
use Firebed\AadeMyData\Http\RequestMyIncome;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\RequestedBookInfo;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\RequestedE3Info;
use Firebed\AadeMyData\Models\RequestedVatInfo;
use Firebed\AadeMyData\Models\ResponseDoc;

class MyData
{
    /**
     * Αποστολή μαζικών ή μεμονωμένων παραστατικών στο myDATA.
     *
     * @param  InvoicesDoc|Invoice|Invoice[]  $invoices  InvoicesDoc
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function sendInvoices(InvoicesDoc|Invoice|array $invoices): ResponseDoc
    {
        return (new SendInvoices())->handle($invoices);
    }

    /**
     * Για ετεροχρονισμένες συναλλαγές, κατά τις οποίες η έκδοση των παραστατικών
     * διενεργείται σε χρόνο προγενέστερο της πληρωμής τους.
     *
     * Κατά τη χρήση της μεθόδου, τουλάχιστον ένα αντικείμενο PaymentMethodDetail
     * ανά παραστατικό πρέπει να είναι τύπου POS.
     *
     * @param  PaymentMethod|PaymentMethod[]  $paymentMethods
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function sendPayments(PaymentMethod|array $paymentMethods): ResponseDoc
    {
        return (new SendPaymentsMethod())->handle($paymentMethods);
    }

    /**
     * Αυτή η POST μέθοδος χρησιμοποιείται για την ακύρωση παραστατικού χωρίς
     * επαναϋποβολή καινούργιου.
     *
     * @param  string  $mark  Μοναδικός αριθμός καταχώρησης παραστατικού προς ακύρωση
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function cancelInvoice(string $mark, string $entityVatNumber = null): ResponseDoc
    {
        return (new CancelInvoice())->handle($mark, $entityVatNumber);
    }

    /**
     * Τμηματική λήψη παραστατικών, χαρακτηρισμών και ακυρώσεων παραστατικών που έχουν υποβάλλει άλλοι χρήστες.
     *
     * @param  string  $mark  Μοναδικός αριθμός καταχώρησης
     * @param  string|null  $dateFrom  Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $dateTo  Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $receiverVatNumber  ΑΦΜ αντισυμβαλλόμενου
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  InvoiceType|string|null  $invType  Τύπος παραστατικού
     * @param  string|null  $maxMark  Μέγιστος Αριθμός ΜΑΡΚ
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedDoc
     * @throws MyDataException
     */
    public function requestInvoices(string $mark = '', string $dateFrom = null, string $dateTo = null, string $receiverVatNumber = null, string $entityVatNumber = null, InvoiceType|string $invType = null, string $maxMark = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedDoc
    {
        return (new RequestDocs())->handle($mark, $dateFrom, $dateTo, $receiverVatNumber, $entityVatNumber, $invType, $maxMark, $nextPartitionKey, $nextRowKey);
    }

    /**
     * Τμηματική λήψη παραστατικών, χαρακτηρισμών και ακυρώσεων παραστατικών που έχουν υποβάλλει ο ίδιος ο χρήστης.
     *
     * @param  string  $mark  Μοναδικός αριθμός καταχώρησης
     * @param  string|null  $dateFrom  Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $dateTo  Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $receiverVatNumber  ΑΦΜ αντισυμβαλλόμενου
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  InvoiceType|string|null  $invType  Τύπος παραστατικού
     * @param  string|null  $maxMark  Μέγιστος Αριθμός ΜΑΡΚ
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedDoc
     * @throws MyDataException
     */
    public function requestTransmittedInvoices(string $mark = '', string $dateFrom = null, string $dateTo = null, string $receiverVatNumber = null, string $entityVatNumber = null, InvoiceType|string $invType = null, string $maxMark = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedDoc
    {
        return (new RequestTransmittedDocs())->handle($mark, $dateFrom, $dateTo, $receiverVatNumber, $entityVatNumber, $invType, $maxMark, $nextPartitionKey, $nextRowKey);
    }

    /**
     * Επιστρέφει γραμμές με πληροφορίες για τα έξοδα του χρήστη.
     *
     * @param  string  $dateFrom  Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string  $dateTo  Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $counterVatNumber  ΑΦΜ αντισυμβαλλόμενου
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  InvoiceType|string|null  $invType  Τύπος παραστατικού
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedBookInfo
     * @throws MyDataException
     */
    public function requestMyExpenses(string $dateFrom, string $dateTo, string $counterVatNumber = null, string $entityVatNumber = null, InvoiceType|string $invType = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedBookInfo
    {
        return (new RequestMyExpenses())->handle($dateFrom, $dateTo, $counterVatNumber, $entityVatNumber, $invType, $nextPartitionKey, $nextRowKey);
    }


    /**
     * Επιστρέφει γραμμές με πληροφορίες για τα έσοδα του χρήστη.
     *
     * @param  string  $dateFrom  Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string  $dateTo  Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $counterVatNumber  ΑΦΜ αντισυμβαλλόμενου
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  InvoiceType|string|null  $invType  Τύπος παραστατικού
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedBookInfo
     * @throws MyDataException
     */
    public function requestMyIncome(string $dateFrom, string $dateTo, string $counterVatNumber = null, string $entityVatNumber = null, InvoiceType|string $invType = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedBookInfo
    {
        return (new RequestMyIncome())->handle($dateFrom, $dateTo, $counterVatNumber, $entityVatNumber, $invType, $nextPartitionKey, $nextRowKey);
    }

    /**
     * Επιστρέφει λεπτομερείς πληροφορίες για τα στοιχεία ΦΠΑ που συνδέονται με τον ΑΦΜ μιας οντότητας
     * για ένα συγκεκριμένο χρονικό διάστημα.
     *
     * @param  string  $dateFrom  Αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param  string  $dateTo  Τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  bool  $groupedPerDay  Παράμετρος που δηλώνει εάν τα αποτελέσματα πρέπει να ομαδοποιηθούν ανά ημέρα.
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων <code>($groupedPerDay = false)</code>
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων <code>($groupedPerDay = false)</code>
     * @return RequestedVatInfo
     * @throws MyDataException
     */
    public function requestVatInfo(string $dateFrom, string $dateTo, string $entityVatNumber = null, bool $groupedPerDay = false, string $nextPartitionKey = null, string $nextRowKey = null): RequestedVatInfo
    {
        return (new RequestVatInfo())->handle($dateFrom, $dateTo, $entityVatNumber, $groupedPerDay, $nextPartitionKey, $nextRowKey);
    }

    /**
     * Επιστρέφει λεπτομερείς πληροφορίες για τα στοιχεία Ε3 που συνδέονται με τον ΑΦΜ μιας οντότητας
     * για ένα συγκεκριμένο χρονικό διάστημα.
     *
     * @param  string  $dateFrom  Αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param  string  $dateTo  Τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  bool  $groupedPerDay  Παράμετρος που δηλώνει εάν τα αποτελέσματα πρέπει να ομαδοποιηθούν ανά ημέρα.
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων <code>($groupedPerDay = false)</code>
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων <code>($groupedPerDay = false)</code>
     * @return RequestedE3Info
     * @throws MyDataException
     */
    public function requestE3(string $dateFrom, string $dateTo, string $entityVatNumber = null, bool $groupedPerDay = false, string $nextPartitionKey = null, string $nextRowKey = null): RequestedE3Info
    {
        return (new RequestE3Info())->handle($dateFrom, $dateTo, $entityVatNumber, $groupedPerDay, $nextPartitionKey, $nextRowKey);
    }
}